<?php
class Cart {
	var $items = array();
	var $total = 0;
	var $id;
	var $holds = array();
	var $lock = 0;
	
	//Constructor
	function Cart($id) {
		global $dbConn;
		debug_message("Initializing Basket");
		$this->id = intval($id);
		$query = $dbConn->query("SELECT * FROM `basket` WHERE id=".$this->id." LIMIT 1");
		if (!$query) debug_message("Error loading basket status.");
		$res = $dbConn->fetch($query);
		$this->lock = $res['lock'];
		if ($res['lock'] == 1) debug_message("Basket locked for editing.");
	}
	
	function restore() {
		debug_message("Restoring Basket");
		$this->Cart($this->id);
		$this->checkPrice();
	}
	
	function checkPrice() {
		$newPrice = 0;
		foreach (array_keys($this->items) as $item) {
			$obj = new Item($item);
			$newPrice += $this->items[$item]*$obj->getPrice();
		}
		$this->total = $newPrice;
	}
	
	function clear() {
		if (!$this->lock) {
			debug_message("Emptying Basket");
			unset($this->items);
			$this->items = array();
			$this->total = 0;
		} else {
			debug_message("Cannot Empty Basket - Basket Locked");
		}
	}
	
	function lock() {
		global $dbConn;
		debug_message("Locking Basket");
		$this->lock = 1;
		$dbConn->query("UPDATE `basket` SET `lock`='1' WHERE id=".$this->id." LIMIT 1");
	}
	
	function unlock() {
		global $dbConn;
		debug_message("Unlocking Basket");
		$this->lock = 0;
		$dbConn->query("UPDATE `basket` SET `lock`='0' WHERE id=".$this->id." LIMIT 1");
	}
	
	function addItem($id) {
		if (!isset($this->items[$id])) $this->items[$id] = 0;
		$this->items[$id]++;
		$item = new Item($id);
		$this->incTotal($item->getPrice());
	}
	
	function removeItem($id) {
		if (isset($this->items[$id])) {
			$item = new Item($id);
			$quantity = $this->items[$id];
			$price = $item->getPrice()*$quantity;
			unset($this->items[$id]);
			$this->incTotal(-$price);
		}
	}
	
	function incTotal($int) {
		$this->total+=$int;
	}
	
	function listItems($mode = "BASKET") {
		global $config;
		$mode = strtoupper($mode);
		if ($mode == "BASKET") {
			$reply = $this->getItems()." Item(s) in Basket.&nbsp;";
			$reply .= "<input type='button' class='ui-state-default' style='cursor: pointer;' onclick='emptyBasket();' value='Empty' />";
			$items = array_keys($this->items);
			foreach ($items as $id) {
				$item = new Item($id);
				$reply .= $item->getDetails("BASKET",$this->items[$id]);
			}
			$reply .= "<hr />";
			$reply .= "<table><tr><td>Subtotal: </td><td>&pound;".$this->getFriendlyTotal(false)."</td></tr>";
			$reply .= "<tr><td>VAT @".$config->getNode('site','vat')."%: </td><td>&pound;".$this->getFriendlyVAT()."</td></tr>";
			$reply .= "<tr class='ui-state-highlight'><td>Total: </td><td>&pound;".$this->getFriendlyTotal(true)."</td></tr></table>";
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
		} else {
			debug_message("Commiting Changes to Basket");
			$query = "UPDATE `basket` SET obj='".base64_encode(serialize($this))."' WHERE id='".$this->id."' LIMIT 1";
			$dbConn->query($query);
		}
	}
	
	function import() {
		global $dbConn;
		if ($dbConn->rows($dbConn->query("SELECT id FROM `basket` WHERE id='".$this->id."' LIMIT 1"))) {
			$query = "UPDATE `basket` SET obj='".base64_encode(serialize($this))."' WHERE id='".$this->id."' LIMIT 1";
		} else {
			$query = "INSERT INTO `basket` (id,obj,`lock`) VALUES (".$this->id.",'".base64_encode(serialize($this))."',".$this->lock.")";
		}
		return $dbConn->query($query);
	}
	
	//Getters
	function getTotal() {
		return $this->total;
	}
	
	function getFriendlyTotal($vat = false) {
		global $config;
		if ($vat) $multiplier = ($config->getNode('site','vat')/100)+1; else $multiplier = 1;
		return number_format($this->total*$multiplier,2);
	}
	
	function getFriendlyVAT() {
		global $config;
		$multiplier = ($config->getNode('site','vat')/100)+1;
		return number_format($this->total*$multiplier,2);
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
				$dbConn->query("INSERT INTO `reserve` (item,quantity,expire) VALUES ($item,".$this->items[$item].",'$expire')");
				$dbConn->query("UPDATE `products` SET stock=stock-".$this->items[$item]." WHERE id=$item LIMIT 1");
				$this->holds[$item] = $dbConn->insert_id();
			}
		} else {
			$expire = $dbConn->time($expire);
			$dbConn->query("INSERT INTO `reserve` (item,quantity,expire) VALUES ($id,".$this->items[$id].",'$expire')");
			$dbConn->query("UPDATE `products` SET stock=stock-".$this->items[$item]." WHERE id=$id LIMIT 1");
			$this->holds[$id] = $dbConn->insert_id();
		}
		return true;
	}
	
	//Cancel Holds on Items
	function cancelHold($id = -1) {
		//-1 = ALL
		if ($id == -1) {
			$items = array_keys($this->items);
			foreach ($items as $item) {
				$dbConn->query("DELETE FROM `reserve` WHERE id=$item LIMIT 1");
				$dbConn->query("UPDATE `products` SET stock=stock+".$this->items[$item]." WHERE id=$item LIMIT 1");
				$this->holds[$item] = $dbConn->insert_id();
			}
		} else {
			$dbConn->query("DELETE `reserve` WHERE id=".$this->holds[$id]." LIMIT 1");
			$dbConn->query("UPDATE `products` SET stock=stock+".$this->items[$item]." WHERE id=$id LIMIT 1");
			unset($this->holds[$id]);
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
				if ($dbConn->rows($dbConn->query("SELECT * FROM `reserve` WHERE id='".$this->holds[$item]."' LIMIT 1")) == 1) {
					//Not timed out
					$dbConn->query("DELETE FROM `reserve` WHERE id=".$this->holds[$item]." LIMIT 1");
				} else {
					//Timed Out/No Hold
					$dbConn->query("UPDATE `items` SET stock=stock-".$this->items[$item]." WHERE id=$item LIMIT 1");
				}
				unset($this->holds[$item]);
			}
		} else {
			$dbConn->query("DELETE `reserve` WHERE id=".$this->holds[$id]." LIMIT 1");
			unset($this->holds[$id]);
		}
		return true;
	}
	
	//Officially Place Order in Database
	//Quite Possibly the most important function in the entire site
	//DON'T BREAK IT
	function commitOrder($customer,$token) {
		global $dbConn, $_PRINTDATA;
		if ($dbConn->rows($dbConn->query("SELECT * FROM `orders` WHERE basket=".$this->id." LIMIT 1")) == 1) return true;
		if (!$dbConn->query("INSERT INTO `orders` (basket,status,token,customer) VALUES (".$this->id.",0,'$token',".$customer->getID().")")) {
			return false;
		}
		else return $dbConn->insert_id();
	}
}
?>