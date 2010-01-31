<?php
class Reserve {
	var $id;
	var $item;
	var $quantity;
	var $expire;
	
	function Reserve($id) {
		global $dbConn;
		$this->id = $id;
		$result = $dbConn->query("SELECT * FROM `reserve` WHERE id=$this->id LIMIT 1");
		$result = $dbConn->fetch($result);
		$this->item = $result['item'];
		$this->quantity = $result['quantity'];
		$this->expire = $result['expire'];
	}
	
	function getID() {
		return $this->id;
	}
	
	function import() {
		global $dbConn;
		if ($dbConn->rows($dbConn->query("SELECT * FROM `news` WHERE id=".$this->id." LIMIT 1"))) {
			$query = "UPDATE `reserve` SET item='".$this->item."', quantity='".$this->quantity."', expire='".$this->expire."' WHERE id=".$this->id." LIMIT 1";
		} else {
			$query = "INSERT INTO `reserve` (id,item,quantity,expire) VALUES ($this->id,'$this->item','$this->quantity','$this->expire')";
		}
		return $dbConn->query($query);
	}
}
?>