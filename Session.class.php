<?php
class Session {
	var $id;
	var $basket;
	var $active;
	var $ipaddr;
	
	function Session($id) {
		global $dbConn;
		$this->id = $id;
		$result = $dbConn->query("SELECT * FROM `sessions` WHERE id='$this->id' LIMIT 1");
		$result = $dbConn->fetch($result);
		$this->basket = $result['basket'];
		$this->active = $result['active'];
		$this->ipaddr = $result['ip_addr'];
	}
	
	function getID() {
		return $this->id;
	}
	
	function import() {
		global $dbConn;
		if ($dbConn->rows($dbConn->query("SELECT * FROM `sessions` WHERE id=".$this->id." LIMIT 1"))) {
			$query = "UPDATE `sessions` SET basket='".$this->basket."', active='".$this->active."', ip_addr='".$this->ipaddr."' WHERE id=".$this->id." LIMIT 1";
		} else {
			$query = "INSERT INTO `sessions` (id,basket,active,ip_addr) VALUES ($this->id,'$this->basket','$this->active','$this->ipaddr')";
		}
		return $query;
	}
}
?>