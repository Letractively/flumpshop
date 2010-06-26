<?php

/**
*  Provides global logic and storage for session basket data
*
*  This file is part of Flumpshop.
*
*  Flumpshop is free software: you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation, either version 3 of the License, or
*  (at your option) any later version.
*
*  Flumpshop is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with Flumpshop.  If not, see <http://www.gnu.org/licenses/>.
*
*
*  @Name Cart.class.php
*  @Version 1.0
*  @author Lloyd Wallis <lloyd@theflump.com>
*  @copyright Copyright (c) 2009-2010, Lloyd Wallis
*  @package Flumpshop
*/

class Cart {
	var $items = array();
	var $total = 0;
	var $delivery = 0;
	var $id;
	var $lock = 0;
	var $change = false;
	var $vatEnabled = true;
	var $keys = array();
	
	/**
    * Cart constructor.
    * @since 1.0
    * @param $id. Optional. The ID of the Cart to load. If unset, creates a new Cart, and assigns itself to the active session.
    * @return void No return value.
    */
	function Cart($id = -1) {
		global $dbConn;
		if ($id == -1) {
			//Initialise an new basket for this session
			debug_message("Initializing Basket");
			$query = $dbConn->query("INSERT INTO `basket` (locked,total,delivery) VALUES (0,0,0)");
			if (!$query) init_err("The system was unable to generate session data correctly. Please try again later. The most likely cause for this issue is the basket database table not existing. dbConn: ".$dbConn->error());
			$this->id = $dbConn->insert_id();
		} elseif ($id == 0) {
			//Search Crawler - Doesn't store
			return;
		} else {
			//Load an existing basket for this session
			debug_message("Loading Basket");
			$this->id = intval($id);
			//Load Basket Parameters
			$query = $dbConn->query("SELECT total,delivery,locked FROM basket WHERE id=".$this->id." LIMIT 1");
			
			if ($dbConn->rows($query) == 0) {
				//The basket doesn't exist - Exit
				init_err("Fatal Error: Basket ID ".$id." does not exist. This is most likely the result of someone deleting this data prematurely, or a cron misconfiguration. dbConn: ".$dbConn->error());
				return;
			} else {
				//Set Basket Properties
				$result = $dbConn->fetch($query);
				$this->price = $result['total'];
				$this->delivery = $result['delivery'];
				$this->lock = $result['locked'];
				unset($query,$result);
			}
			//Load list of items
			$query = $dbConn->query("SELECT item_id,quantity FROM basket_items WHERE basket_id=".$this->id);
			while ($result = $dbConn->fetch($query)) {
				//Create each item
				$this->items[$result['item_id']] = $result['quantity'];
			}
			unset($result,$query);
		}
	}
	
	/**
    * Defines whether to apply VAT to this cart
    * @since 1.0
    * @param bool $bool Optional. Default true. Whether to apply VAT to this cart.
	* @return void No return value.
    */
	function applyVAT($bool = true) {
		if ($bool != $this->vatEnabled) {
			$this->change = true;
			$this->vatEnabled = $bool;
		}
		return true;
	}
	
	/**
    * Add a key to the cart (or ignore if it is already applied)
    * @since 1.0
    * @param int $keyID Required. The ID of the key to add to the cart.
	* @return void No return value.
    */
	function addKey($keyID) {
		$this->change = true;
		$this->keys[$keyID] = true;
	}
	
	/**
    * Updates the total prices variables, to ensure everything is correct
    * @since 1.0
    * @param No arguments.
	* @return void No return value.
    */
	function checkPrice() {
		$newPrice = 0;
		$newDelivery = 0;
		foreach (array_keys($this->items) as $item) {
			$obj = new Item($item);
			$newDelivery += $this->items[$item]*$obj->getDeliveryCost();
			$newPrice += $this->items[$item]*$obj->getPrice();
		}
		$this->total = $newPrice;
		$this->delivery = $newDelivery;
	}
	
	/**
    * Completely empties the Cart of all items
    * @since 1.0
    * @param No arguments.
    * @return void No return value.
    */
	function clear() {
		if (!$this->lock) {
			global $dbConn;
			debug_message("Emptying Basket");
			$dbConn->query("DELETE FROM `basket_items` WHERE basket_id=".$this->id);
			unset($this->items);
			$this->items = array();
			$this->total = 0;
			$this->delivery = 0;
			$this->change = true;
		} else {
			debug_message("Cannot Empty Basket - Basket Locked");
		}
	}
	
	/**
    * Locks the basket, stopping it from being edited later.
	* Used to prevent a user from changing their order after they've started the checkout process
    * @since 1.0
    * @param No arguments.
    * @return void No return value.
    */
	function lock() {
		global $dbConn;
		debug_message("Locking Basket");
		$this->lock = 1;
		$dbConn->query("UPDATE `basket` SET `locked`='1' WHERE id=".$this->id." LIMIT 1");
	}
	
	/**
    * Unlocks the basket, allowing the user to edit it again if they cancel the checkout prcess.
    * @since 1.0
    * @param No arguments.
    * @return void No return value.
    */
	function unlock() {
		global $dbConn;
		debug_message("Unlocking Basket");
		$this->lock = 0;
		$dbConn->query("UPDATE `basket` SET `locked`='0' WHERE id=".$this->id." LIMIT 1");
	}
	
	/**
    * Adds an item to the basket, or increases the quantity if it is already there
    * @since 1.0
    * @param $id. Required. The Unique ID Number of the item to be added.
	* @param int $stock Optional. The quantity of this item to be added. Default 1.
	* @param float $price Optional. If set, this is the price to use for the TOTAL (price*quantity) of the item.
	* Default -1 (Use default from DB). Used in ACP Order Form to provide customers with realtime discounts
    * @return void No return value.
    */
	function addItem($id,$stock=1,$price = -1) {
		global $dbConn;
		$id = intval($id); //Remove zerofill
		if (!isset($this->items[$id])) {
			//None added yet, create new product entry
			$this->items[$id] = $stock;
			$dbConn->query("INSERT INTO `basket_items`
							(item_id,basket_id,quantity,sold_at) VALUES
							(".$id.",".$this->id.",".$stock.",
							".$stock."*(SELECT price FROM `products` WHERE id=".$id."))");
		} else {
			//Increment current total
			$this->changeQuantity($id,$this->items[$id]+$stock);
		}
		$this->change = true;
	}
	
	
	/**
    * Removes the item with the specified ID from the basket.
    * @since 1.0
    * @param int $id Required. The unique ID of the item to remove.
    * @return void No return value.
    */
	function removeItem($id) {
		global $dbConn;
		$id = intval($id); //Remove zerofill
		if (isset($this->items[$id])) {
			$dbConn->query("DELETE FROM `basket_items` WHERE item_id=$id AND basket_id=".$this->id." LIMIT 1");
			unset($this->items[$id]);
			$this->change = true;
		}
	}
	
	/**
    * Sets the quantity (not increment) of an item that is already in the basket
    * @since 1.0
    * @param int $itemid Required. The unique ID of the item to be added.
	* @param int $stock Required. The quantity of the item.
    * @return void No return value.
    */
	function changeQuantity($itemid,$stock) {
		global $dbConn;
		$stock = intval($stock); //Validation. No SQL Injection for You!
		$itemid = intval($itemid); //Remove zerofill
		$this->items[$itemid] = $stock;
		$dbConn->query("UPDATE `basket_items`
						SET quantity=$stock,
						sold_at = ".$this->items[$itemid]."*(SELECT price FROM `products` WHERE id=".$itemid.")
						WHERE item_id=$itemid AND basket_id=".$this->id." LIMIT 1");
		$this->change = true;
	}
	
	/**
    * Get the quantity of the specified item
    * @since 1.0
    * @param int $itemID Required. The Unique ID of the item.
    * @return int|bool An integer representing the quantity of the item, or false if the item has not been added
    */
	function getQuantity($itemID) {
		return $this->items[$itemID];
	}
	
	/**
    * Private function. Used to increment the basket total by the specified amount.
    * @since 1.0
    * @param int $int Required. An integer of float value to increment the total by.
    * @return void No return value.
    */
	function incTotal($int) {
		$this->total+=$int;
	}
	
	/**
    * Private function. Used to increment the basket delivery total by the specified amount.
    * @since 1.0
    * @param int $int Required. An integer of float value to increment the total by.
    * @return void No return value.
    */
	function incDelivery($int) {
		$this->delivery+=$int;
	}
	
	/**
    * Returns a user-readable summary of the items in the basket.
    * @since 1.0
    * @param string $mode Optional. What format to output the data as. Default BASKET.
    * @return string A HTML list of items.
    */
	function listItems($mode = "BASKET") {
		global $config;
		$mode = strtoupper($mode);
		if ($mode == "BASKET") {
			$reply = $this->getItems()." Item(s) in Basket.&nbsp;";
			$reply .= "<input type='button' class='ui-state-default' style='cursor: pointer;' onclick='emptyBasket();' value='Empty' />";
			$reply .= "<table style='width:100%' id='basketItemsTable'><tr class='ui-widget-header'><th>Item</th><th>Price</th><th>Quantity</th></tr>";
			$items = array_keys($this->items);
			foreach ($items as $id) {
				$item = new Item($id);
				$reply .= $item->getDetails("BASKET",$this->items[$id]);
			}
			$reply .= "</table>";
			$reply .= "<hr />";
			$reply .= "<table><tr><td>Subtotal: </td><td>&pound;".$this->getFriendlyTotal(false,false)."</td></tr>";
			$reply .= "<tr><td>VAT @".$config->getNode('site','vat')."%: </td><td>&pound;".$this->getFriendlyVAT()."</td></tr>";
			$reply .= "<tr><td>Shipping: </td><td>&pound;".$this->getFriendlyDelivery()."</td></tr>";
			$reply .= "<tr class='ui-state-highlight'><td>Total: </td><td>&pound;".$this->getFriendlyTotal(true,true)."</td></tr></table>";
		} elseif ($mode == "ORDER") {
			$reply = "";
			$items = array_keys($this->items);
			foreach ($items as $id) {
				$item = new Item($id);
				$reply .= $item->getDetails("ORDER",$this->items[$id]);
			}
		}
		return $reply;
	}
	
	/**
    * Cart destructor. Used to save changes to the basket data in the database.
	* Manually called by register_shutdown_function in PHP Versions < 5
    * @since 1.0
    * @param void No arguments.
    * @return void No return value -- object is destroyed after function execution
    */
	function __destruct() {
		global $dbConn;
		if ($this->lock == 1) {
			debug_message("Cannot Commit Basket - Editing Locked by Database");
		} elseif ($this->change) {
			debug_message("Commiting Changes to Basket");
			$this->checkPrice();
			$query = "UPDATE `basket` SET total='".$this->price."', delivery='".$this->delivery."', vat='".$this->vatEnabled."' WHERE id='".$this->id."' LIMIT 1";
			if (!$dbConn->query($query)) {
				trigger_error("Could not save basket. dbConn: ".$dbConn->error());
			}
		}
	}
	
	/**
    * Returns a float value representing the current total value of the cart.
    * @since 1.0
    * @param void No arguments.
    * @return float A float value representing the current total value of the cart.
    */
	function getTotal() {
		return $this->total;
	}
	
	/**
    * Returns a user-readable string representing the current total value of the cart.
    * @since 1.0
    * @param bool $vat Optional. Whether the total should include tax. Default false.
	* @param bool $delivery Optional. Whether the total should include delivery. Default false.
    * @return string A string representing the current total value of the cart, to 2 decimal places.
    */
	function getFriendlyTotal($vat = false, $delivery = false) {
		global $config;
		if ($vat) $multiplier = ($config->getNode('site','vat')/100)+1; else $multiplier = 1;
		if ($delivery) {
			return number_format(($this->total*$multiplier)+$this->delivery,2);
		} else {
			return number_format($this->total*$multiplier,2);
		}
	}
	
	/**
    * Returns a user-readable string representing the tax that will be applied to this order.
    * @since 1.0
    * @param void No arguments.
    * @return string A string representing the tax that will be applied to this order.
    */
	function getFriendlyVAT() {
		global $config;
		if ($this->vatEnabled) {
			$multiplier = ($config->getNode('site','vat')/100);
			return number_format($this->total*$multiplier,2);
		} else {
			return $this->getFriendlyTotal(false,false);
		}
	}
	
	/**
    * Returns a user-readable string representing the delivery that will be applied to this order.
    * @since 1.0
    * @param void No arguments.
    * @return string A string representing the delivery cost of this order, to 2 d.p.
    */
	function getFriendlyDelivery() {
		return number_format($this->delivery,2);
	}
	
	/**
    * Returns the number of items in the cart.
    * @since 1.0
    * @param void No arguments.
    * @return int The current number of unique items in the cart.
    */
	function getItems() {
		return sizeof($this->items);
	}
	
	/**
    * Returns the global unique identifier of this cart.
    * @since 1.0
    * @param void No arguments.
    * @return int The unique ID of this cart.
    */
	function getID() {
		return $this->id;
	}
	
	/**
    * Checks whether there is sufficient stock for all of the items in the order.
    * @since 1.0
    * @param void No arguments.
    * @return bool|int True if there is sufficient stock, or the ID number of the first item found with insufficient stock.
    */
	function checkAvail() {
		global $_PRINTDATA;
		$items = array_keys($this->items);
		foreach ($items as $pid) {
			debug_message("Checking Stock for product id #$pid");
			$item = new Item($pid);
			if (!$item->checkStock($this->items[$pid])) {
				return $pid;
				debug_message("Insufficient Stock.");
			}
		}
		return true;
	}
	
	/**
    * Temporarily mark stock for order as used, to prevent over-ordering.
    * @since 1.0
    * @param int $expire Required. When this temporary lock should expire
	* @param int $id Optional. Which item id number to lock. -1 means all items in basket. Default -1.
    * @return bool Always returns true.
    */
	function holdItem($expire,$id = -1) {
		global $dbConn, $_PRINTDATA;
		$this->checkPrice();
		$expire = intval($expire);
		$expire = date("Y-m-d h:m:s",$expire);
		debug_message("Hold Expiry: $expire");
		//-1 = ALL
		if ($id == -1) {
			$items = array_keys($this->items);
			foreach ($items as $item) {
				$dbConn->query("INSERT INTO `reserve` (item,quantity,expire,basketID) VALUES
								($item,".$this->items[$item].",'$expire',".$this->id.")");
				$dbConn->query("UPDATE `products` SET stock=stock-".$this->items[$item]." WHERE id=$item LIMIT 1");
			}
		} else {
			$expire = $dbConn->time($expire);
			$dbConn->query("INSERT INTO `reserve` (item,quantity,expire,basketID) VALUES 
							($id,".$this->items[$id].",'$expire','".$this->id."')");
			
			$dbConn->query("UPDATE `products` SET stock=stock-".$this->items[$item]." WHERE id=$id LIMIT 1");
		}
		return true;
	}
	
	/**
    * Remove temporary locks on stock in the event of a user cancelling the checkout process.
    * @since 1.0
    * @param int $id Optional. Which item id number to unlock. -1 means all items in basket. Default -1.
    * @return bool Always returns true.
    */
	function cancelHold($id = -1) {
		//-1 = ALL
		if ($id == -1) {
			$items = array_keys($this->items);
			foreach ($items as $item) {
				$dbConn->query("DELETE FROM `reserve` WHERE item=$item AND basketID=".$this->id." LIMIT 1");
				$dbConn->query("UPDATE `products` SET stock=stock+".$this->items[$item]." WHERE id=$item LIMIT 1");
				$this->holds[$item] = $dbConn->insert_id();
			}
		} else {
			$dbConn->query("DELETE `reserve` WHERE item=".$this->holds[$id]." AND basketID=".$this->id." LIMIT 1");
			$dbConn->query("UPDATE `products` SET stock=stock+".$this->items[$item]." WHERE id=$id LIMIT 1");
		}
		return true;
	}
	
	//Permanently Marks Items Stock Down
	/**
    * Permanently mark the stock of item(s) in the cart as down
    * @since 1.0
    * @param int $id Optional. Which item id number to commit. -1 means all items in basket. Default -1.
    * @return bool Always returns true.
    */
	function commitHolds($id = -1) {
		global $dbConn;
		//-1 = ALL
		if ($id == -1) {
			$items = array_keys($this->items);
			foreach ($items as $item) {
				//Has it already timed out?
				if ($dbConn->rows($dbConn->query("SELECT * FROM `reserve` WHERE item='".$this->holds[$item]."' AND basketID=".$this->ID." LIMIT 1")) == 1) {
					//Not timed out
					$dbConn->query("DELETE FROM `reserve` WHERE item=".$this->holds[$item]." AND basketID=".$this->id." LIMIT 1");
				} else {
					//Timed Out/No Hold
					$dbConn->query("UPDATE `products` SET stock=stock-".$this->items[$item]." WHERE id=$item LIMIT 1");
				}
				unset($this->holds[$item]);
			}
		} else {
			$dbConn->query("DELETE `reserve` WHERE item=".$id." AND basketID=".$this->id." LIMIT 1");
		}
		return true;
	}
	
	/**
    * Finalizes the order by committing it to the database
    * @since 1.0
    * @param Customer $billing Required. A Customer object to use for the order billing address.
	* @param Customer|int $shipping Optional. A Customer object to use for the order shipping address. 0 for same as billing. Default 0.
	* @param string $token Optional. The PayPal token generated by the order, if available. Default empty string.
    * @return bool|int Returns false on failure, or the Order ID on success.
    */
	function commitOrder($billing,$shipping = 0,$token = '') {
		global $dbConn;
		if ($dbConn->rows($dbConn->query("SELECT * FROM `orders` WHERE basket=".$this->id." LIMIT 1")) == 1) return true;
		if (!$dbConn->query("INSERT INTO `orders` (basket,status,token,billing,shipping) VALUES (".$this->id.",0,'$token',".$billing->getID().",".$shipping->getID().")")) {
			return false;
		}
		else return $dbConn->insert_id();
	}
}
?>