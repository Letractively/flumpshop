<?php

/*
* ====================================================================
*  Name        : Cart.class.php
*  Description : Provides global logic and storage for session
*				 basket data
*  Version     : 1.0
*
*  Copyright (c) 2009-2010 Lloyd Wallis, lloyd@theflump.com
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
* ====================================================================
*/

class Cart {
	var $items = array();
	var $total = 0;
	var $delivery = 0;
	var $id;
	var $lock = 0;
	var $change = false;
	
	/**
    * Cart constructor.
    * @since 1.0
    * @param $id. Optional. The ID of the Cart to load. If unset, creates a new Cart, and assigns itself to the active session.
    * @return No return value.
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
	
	function restore() {
		//Depreciated. No longer used with new normalized Database system
		return $this->checkPrice();
	}
	
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
	
	function lock() {
		global $dbConn;
		debug_message("Locking Basket");
		$this->lock = 1;
		$dbConn->query("UPDATE `basket` SET `locked`='1' WHERE id=".$this->id." LIMIT 1");
	}
	
	function unlock() {
		global $dbConn;
		debug_message("Unlocking Basket");
		$this->lock = 0;
		$dbConn->query("UPDATE `basket` SET `locked`='0' WHERE id=".$this->id." LIMIT 1");
	}
	
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
			$this->items[$id]+=$stock;
			$dbConn->query("UPDATE `basket_items`
							SET quantity = ".$this->items[$id].",
							sold_at = ".$this->items[$id]."*(SELECT price FROM `products` WHERE id=".$id.")
							WHERE item_id=$id AND basket_id=".$this->id." LIMIT 1");
		}
		$this->change = true;
	}
	
	function removeItem($id) {
		global $dbConn;
		$id = intval($id); //Remove zerofill
		if (isset($this->items[$id])) {
			$dbConn->query("DELETE FROM `basket_items` WHERE item_id=$id AND basket_id=".$this->id." LIMIT 1");
			unset($this->items[$id]);
			$this->change = true;
		}
	}
	
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
	
	function getQuantity($itemID) {
		return $this->items[$itemID];
	}
	
	function incTotal($int) {
		$this->total+=$int;
	}
	
	function incDelivery($int) {
		$this->delivery+=$int;
	}
	
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
	
	function __destruct() {
		global $dbConn;
		if ($this->lock == 1) {
			debug_message("Cannot Commit Basket - Editing Locked by Database");
		} elseif ($this->change) {
			debug_message("Commiting Changes to Basket");
			$this->checkPrice();
			$query = "UPDATE `basket` SET total='".$this->price."', delivery='".$this->delivery."' WHERE id='".$this->id."' LIMIT 1";
			if (!$dbConn->query($query)) {
				trigger_error("Could not save basket. dbConn: ".$dbConn->error());
			}
		}
	}
	
	//Getters
	function getTotal() {
		return $this->total;
	}
	
	function getFriendlyTotal($vat = false, $delivery = false) {
		global $config;
		if ($vat) $multiplier = ($config->getNode('site','vat')/100)+1; else $multiplier = 1;
		if ($delivery) {
			return number_format(($this->total*$multiplier)+$this->delivery,2);
		} else {
			return number_format($this->total*$multiplier,2);
		}
	}
	
	function getFriendlyVAT() {
		global $config;
		$multiplier = ($config->getNode('site','vat')/100);
		return number_format($this->total*$multiplier,2);
	}
	
	function getFriendlyDelivery() {
		return number_format($this->delivery,2);
	}
	
	function getItems() {
		return sizeof($this->items);
	}
	
	function getID() {
		return $this->id;
	}
	
	//Check Stock on Items
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
	
	//Temporarily mark stock as taken
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
	
	//Cancel Holds on Items
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
	
	//Officially Place Order in Database
	//Quite Possibly the most important function in the entire site
	//DON'T BREAK IT
	function commitOrder($customer,$token) {
		global $dbConn;
		if ($dbConn->rows($dbConn->query("SELECT * FROM `orders` WHERE basket=".$this->id." LIMIT 1")) == 1) return true;
		if (!$dbConn->query("INSERT INTO `orders` (basket,status,token,customer) VALUES (".$this->id.",0,'$token',".$customer->getID().")")) {
			return false;
		}
		else return $dbConn->insert_id();
	}
}
?>