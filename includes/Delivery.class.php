<?php
class Delivery {
	var $id;
	var $country;
	var $lowerBound;
	var $upperBound;
	var $price;
	
	function Delivery($id) {
		global $dbConn;
		$this->id = $id;
		$result = $dbConn->query("SELECT * FROM `delivery` WHERE id='$id' LIMIT 1");
		$result = $dbConn->fetch($result);
		$this->country = $result['country'];
		$this->lowerBound = $result['lowerbound'];
		$this->upperBound = $result['upperbound'];
		$this->price = $result['price'];
	}
	
	function getID() {
		return $this->id;
	}
	
	function import() {
		global $dbConn;
		if ($dbConn->rows($dbConn->query("SELECT * FROM `delivery` WHERE id=".$this->id." LIMIT 1"))) {
			$query = "UPDATE `delivery` SET country='$this->country',lowerbound='$this->lowerBound',upperbound='$this->upperBound',price='$this->price' WHERE id=".$this->id." LIMIT 1";
		} else {
			$query = "INSERT INTO `delivery` (id,country,lowerbound,upperbound,price) VALUES ($this->id,'$this->country','$this->lowerBound','$this->upperBound','$this->price')";
		}
		return $dbConn->query($query);
	}
}
?>