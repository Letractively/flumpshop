<?php
class Customer {
	//Vars
	var $id;
	var $name;
	var $address1;
	var $address2;
	var $address3;
	var $postcode;
	var $country;
	var $countryName;
	var $email;
	var $paypalid;
	var $can_contact;
	var $supportedCountry = false;
	
	//Constructor
	function Customer($id = NULL) {
		global $dbConn, $config;
		if ($id != NULL) {
			$result = $dbConn->query("SELECT * FROM `customers` WHERE id='$id' LIMIT 1");
			$result = $dbConn->fetch($result);
			$this->id = $result['id'];
			$this->name = $result['name'];
			$this->address1 = $result['address1'];
			$this->address2 = $result['address2'];
			$this->address3 = $result['address3'];
			$this->postcode = $result['postcode'];
			$this->country = $result['country'];
			$this->email= $result['email'];
			$this->paypalid = $result['paypalid'];
			$this->can_contact = $result['can_contact'];
			
			$country = $dbConn->fetch($dbConn->query("SELECT * FROM `country` WHERE iso='".$this->country."' LIMIT 1"));
			$this->supportedCountry = $country['supported'];
			$this->countryName = $country['name'];
		} else {
			$this->id = 0;
		}
	}
	
	//Destructor
	function __destruct() {
		global $dbConn, $config;
		if (!is_object($dbConn)) {init_err("dbConn is not an object!");die();}
		debug_message("Commiting Changes to Customer");
		$dbConn->query("UPDATE `customers` SET name='$this->name',address1='$this->address1',address2='$this->address2',address3='$this->address3',postcode='$this->postcode',country='$this->country',email='$this->email',paypalid='$this->paypalid' WHERE id=$this->id");
	}
	
	function import() {
		global $dbConn;
		if ($dbConn->rows($dbConn->query("SELECT * FROM `customers` WHERE id=".$this->id." LIMIT 1"))) {
			$query = "UPDATE `customers` SET name='$this->name',address1='$this->address1',address2='$this->address2',address3='$this->address3',postcode='$this->postcode',country='$this->country',email='$this->email',paypalid='$this->paypalid' WHERE id=$this->id";
		} else {
			$query = "INSERT INTO `customers` (id,name,address1,address2,address3,postcode,country,email,paypalid) VALUES ({$this->id},'$this->name','$this->address1','$this->address2','$this->address3','$this->postcode','$this->country','$this->email','$this->paypalid')";
		}
		return $dbConn->query($query);
	}
	
	//Setters
	function populate($name = NULL,$address1 = NULL,$address2 = NULL,$address3 = NULL,$postcode = NULL,$country = NULL,$email = NULL,$paypalid = NULL,$can_contact = 1) {
		global $dbConn;
		$vars = array("name","address1","address2","address3","postcode","country","email","paypalid","can_contact");
		foreach ($vars as $var) {
			if ($$var != NULL) {
				$this->$var = str_replace("'","''",$$var);
			}
		}
		if ($this->id == 0) {
			$dbConn->query("INSERT INTO `customers` (name,address1,address2,address3,postcode,country,email,paypalid,archive,can_contact) VALUES ('$this->name','$this->address1','$this->address2','$this->address3','$this->postcode','$this->country','$this->email','$this->paypalid',0,'$this->can_contact')");
			$this->id = $dbConn->insert_id();
		}
		$country = $dbConn->fetch($dbConn->query("SELECT * FROM `country` WHERE iso='".$this->country."' LIMIT 1"));
		$this->supportedCountry = $country['supported'];
		$this->countryName = $country['name'];
	}
	
	//Getters
	function getID() {
		return $this->id;
	}
	function getName() {
		return $this->name;
	}
	function getAddress1() {
		return $this->address1;
	}
	function getAddress2() {
		return $this->address2;
	}
	function getAddress3() {
		return $this->address3;
	}
	function getPostcode() {
		return $this->postcode;
	}
	function getCountry() {
		return $this->country;
	}
	function getCountryName() {
		return $this->countryName;
	}
	function getEmail() {
		return $this->email;
	}
	function getPaypalID() {
		return $this->paypalid;
	}
	
	function getAddress() {
		return $this->getAddress1()."<br />".$this->getAddress2()."<br />".$this->getAddress3()."<br />".$this->getPostcode()."<br />".$this->getCountryName();
	}
	
	function deliverySupported() {
		return $this->supportedCountry;
	}
}
?>