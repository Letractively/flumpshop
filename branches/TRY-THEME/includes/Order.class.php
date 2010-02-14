<?php
class Order {
	var $id;
	var $basket;
	var $status;
	var $token;
	var $customer;
	
	function Order($id) {
		global $dbConn;
		$this->id = $id;
		$result = $dbConn->query("SELECT * FROM `orders` WHERE id=$this->id LIMIT 1");
		$result = $dbConn->fetch($result);
		$this->basket = $result['basket'];
		$this->status = $result['status'];
		$this->token = $result['token'];
		$this->customer = $result['customer'];
	}
	
	function getID() {
		return $this->id;
	}
	
	function import() {
		global $dbConn;
		if ($dbConn->rows($dbConn->query("SELECT * FROM `orders` WHERE id=".$this->id." LIMIT 1"))) {
			$query = "UPDATE `orders` SET basket='".$this->basket."', status='".$this->status."', token='".$this->token."', customer='".$this->customer."' WHERE id=".$this->id." LIMIT 1";
		} else {
			$query = "INSERT INTO `orders` (id,basket,status,token,customer) VALUES ($this->id,'$this->basket','$this->status','$this->token','$this->customer')";
		}
		return $dbConn->query($query);
	}
}
?>