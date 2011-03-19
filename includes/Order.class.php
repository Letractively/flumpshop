<?php

/**
*  Stores and manages orders on the Flumpshop platform
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
*  @Name        : Order.class.php
*  @Version     : 1.01
*  @author		: Lloyd Wallis <flump5281@gmail.com>
*  @copyright	: Copyright (c) 2009-2011, Lloyd Wallis
*/

class Order {
	var $id;
	var $basket;
	var $status;
	var $token;
	var $billing;
	var $shipping;
	/**
	 * This is set to false if the Constructor's SELECT query returns 0 rows, i.e. there is not order with that ID
	 * @since 1.01
	 * @var bool Whether the order exists
	 */
	var $exists;
	/**
	 * @since 1.01
	 * @var int The ID of the ACP user this order is assigned to
	 */
	var $assigned_to;
	
	/**
    * Order constructor.
    * @since 1.0
    * @param int $id Required. The ID of the Order to load. Does NOT create an Order if not set.
    * @return void No return value.
    */
	function Order($id) {
		global $dbConn;
		$this->id = $id;
		$result = $dbConn->query('SELECT * FROM `orders` WHERE id="'.$this->id.'" LIMIT 1');
		$this->exists = ($dbConn->rows($result) == 1);
		if (!$this->exists) return; //Stop here if it wasn't found
		$result = $dbConn->fetch($result);
		$this->basket = $result['basket'];
		$this->status = $result['status'];
		$this->token = $result['token'];
		$this->billing = $result['billing'];
		$this->shipping = $result['shipping'];
		$this->assigned_to = $result['assignedTo'];
	}

	/**
	 * Returns whether the order this object represents exists
	 * @since 1.01
	 * @return bool If the order exists
	 */
	function getExists() {
		return $this->exists;
	}

	/**
	 * Outputs the basket object for this order
	 * @since 1.01
	 * @return Cart a Flumpshop Cart object
	 */
	function getBasketObj() {
		return new Cart($this->basket);
	}
	
	/**
    * Creates an order from the ACP Order Screen
	* @static This function is to be run with out instantiating an instance of Order
    * @since 1.0
    * @param No params.
    * @return Order An instantiated Order object populated with data fomr $_POST
    */
	function createFromOrderScreen() {
		global $_POST, $dbConn;
		$basket = new Cart();
		
		//This iterates over each item in the order items row until a row is not defined, which marks the end of the table
		for ($i=1; isset($_POST['item'.$i.'ID']); $i++) {
			//This order item row exists, create an item in the cart based on this
			
			//Skip this row if the item's quantity is 0
			if ($_POST['item'.$i.'Qty'] == 0) continue;
			//Skip Final ID
			if ($_POST['item'.$i.'ID'] == '') continue;
			
			//Pull the price from the post form in case the user has set a custom price
			$basket->addItem($_POST['item'.$i.'ID'], $_POST['item'.$i.'Qty'], floatval(str_replace('&pound;','',$_POST['item'.$i.'Price'])));
		}
		
		//Iterate through coupons and create a reference to each one
		//TODO: The system does not currently apply the actions on the server
		for ($i=1; isset($_POST['coupon'.$i.'Key']); $i++) {
			$result = $dbConn->query('SELECT id FROM keys WHERE `key` = "'.$_POST['coupon'.$i.'Key'].'" LIMIT 1');
			$result = $dbConn->fetch($result);
			$keyID = $result['id'];
			unset($result);
			$basket->addKey($keyID);
		}
		
		//Set whether VAT has been applied to this order
		$basket->applyVAT(!isset($_POST['vatExempt']));
		
		//Set up the customer for the billing address
		if ($_POST['billingID'] != "New") {
			//The customer already exists. Just use that
			$billing = new Customer($_POST['billingID']);
		} else {
			//The customer is new, create an ID for them now
			$billing = new Customer();
			$billing->populate($_POST['customerBillingName'],
								$_POST['customerBillingAddress1'],
								$_POST['customerBillingAddress2'],
								$_POST['customerBillingAddress3'],
								$_POST['customerBillingPostcode'],
								$_POST['customerBillingCountry'],
								NULL, //TODO: Add Email support
								NULL,
								1 //TODO: Contact Opt-out
								);
			
		}
		
		//Check if the shipping address is configured
		if (isset($_POST['noShipping'])) {
			//The shipping address is the same as billing. Just take from there
			$shipping = @$billing; //@ Saves memory by using an alias
		} else {
			//The shipping address is different. Load it the same as before
			if ($_POST['shippingID'] != "New") {
				//The customer already exists. Just use that
				$shipping = new Customer($_POST['shippingID']);
			} else {
				//The customer is new, create an ID for them now
				$shipping = new Customer();
				$shipping->populate($_POST['customerShippingName'],
									$_POST['customerShippingAddress1'],
									$_POST['customerShippingAddress2'],
									$_POST['customerShippingAddress3'],
									$_POST['customerShippingPostcode'],
									$_POST['customerShippingCountry'],
									NULL, //TODO: Add Email support
									NULL,
									1 //TODO: Contact Opt-out
									);
				
			}
		}
		
		//That's all the data passed. Create the Order.
		$basketID = $basket->getID();
		$orderStatus = $_POST['orderStatus'];
		$basket->__destruct(); //Force call for PHP4
		unset($basket);
		
		$billingID = $billing->getID();
		
		$shippingID = $shipping->getID();
		$billing->__destruct();
		$shipping->__destruct();
		unset($billing,$shipping); //Unset after due to potential alias
		
		$dbConn->query('INSERT INTO orders (basket,status,billing,shipping,assignedTo)
			VALUES ('.$basketID.',"'.$orderStatus.'",'.$billingID.','.$shippingID.','.$GLOBALS['acp_uid'].')');
		
		return new Order($dbConn->insert_id());
	}
	
	function getID() {
		return $this->id;
	}
	
	function import() {
		global $dbConn;
		if ($dbConn->rows($dbConn->query("SELECT * FROM `orders` WHERE id=".$this->id." LIMIT 1"))) {
			$query = "UPDATE `orders` SET basket='".$this->basket."', status='".$this->status."', token='".$this->token."', billing='".$this->billing."', shipping='".$this->shipping."' WHERE id=".$this->id." LIMIT 1";
		} else {
			$query = "INSERT INTO `orders` (id,basket,status,token,billing,shipping) VALUES ($this->id,'$this->basket','$this->status','$this->token','$this->billing','$this->shipping')";
		}
		return $dbConn->query($query);
	}
}
?>