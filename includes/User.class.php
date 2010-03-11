<?php
class User {
	var $id = 0;
	var $uname = "Unknown";
	var $customer;
	var $customerID;
	var $customerExists;
	var $password;
	var $active;
	var $canContact;
	
	function User($id = 0) {
		global $dbConn;
		$this->id = $id;
		$result = $dbConn->query("SELECT * FROM `login` WHERE id='$id' LIMIT 1");
		if ($result = $dbConn->fetch($result)) {
			$this->uname = $result['uname'];
			$this->password = $result['password'];
			$this->customerID = $result['customer'];
			$this->active = $result['active'];
			$this->canContact = $result['can_contact'];
			if ($result['customer'] == 0) {
				$this->customer = new Customer();
				$this->customerExists = false;
			} else {
				$this->customer = new Customer($result['customer']);
				$this->customerExists = true;
			}
		}
	}
	
	function populate($uname,$password,$customerID = 0,$active = 0) {
		global $dbConn;
		$this->uname = $uname;
		$this->password = $password;
		$this->customerID = $customerID;
		$this->active = $active;
		if ($this->id != 0) {
			$query = "UPDATE `login` SET uname='".$this->uname."', password='".$this->password."', customer=".$this->customerID.", active=$this->active WHERE id=".$this->id." LIMIT 1";
			return $dbConn->query($query);
		} else {
			$query = "INSERT INTO `login` (uname,password,customer,active) VALUES ('$this->uname','$this->password','$this->customerID',$this->active)";
			if (!$dbConn->query($query)) return false;
			$this->id = $dbConn->insert_id();
			return true;
		}
	}
	
	function getUname() {
		return $this->uname;
	}
	
	function getCustomerExists() {
		return $this->customerExists;
	}
	
	function getID() {
		return $this->id;
	}
	
	function activate() {
		global $dbConn;
		$this->active = true;
		return $dbConn->query("UPDATE `login` SET active=1 WHERE id={$this->id} LIMIT 1");
	}
	
	function replaceCustomerObj($newObj) {
		global $dbConn;
		$this->customer = $newObj;
		$this->customerID = $this->customer->getID();
		return $dbConn->query("UPDATE `login` SET customer=".$this->customerID." WHERE id=".$this->id." LIMIT 1");
	}
	
	function import() {
		global $dbConn;
		if ($dbConn->rows($dbConn->query("SELECT * FROM `login` WHERE id=".$this->id." LIMIT 1"))) {
			$query = "UPDATE `login` SET uname='".$this->uname."', password='".$this->password."', customer=".$this->customerID.", active={$this->active} WHERE id=".$this->id." LIMIT 1";
		} else {
			$query = "INSERT INTO `login` (id,uname,password,customer,active) VALUES ($this->id,'$this->uname','$this->password','$this->customerID',$this->active)";
		}
		return $dbConn->query($query);
	}
}
?>