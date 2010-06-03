<?php
/*
* ====================================================================
*  Name        : Keycode.class.php
*  Description : Provides global logic for Keycodes used for various
				 functionality
*  Version     : 1.0
*
*  Copyright (c) 2010 Lloyd Wallis, lloyd@theflump.com
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

class Keycode {
	var $id;
	var $key;
	var $expiryTime;
	var $actions = array();
	var $expiryActions = array();
	
	/**
    * Keycode constructor.
    * @since 1.0
    * @param $id. Optional. The ID of the Keycode to load. If unset, creates and empty object. Default -1.
	* @param $key. Optional. If specified, the object will attempt to load using the keycode instead of the ID.
	* Use $id = -1 to specify search by key
    * @return No return value.
    */
	function Keycode($id = -1, $key = NULL) {
		$id = intval($id);
		global $dbConn;
		//Load By ID
		if ($id != -1) {
			$result = $dbConn->query("SELECT `key`,expiry FROM `keys` WHERE id='".$id."' LIMIT 1");
			if ($dbConn->rows($result) == 0) {
				//ID Doesn't exist.
				trigger_error("Keycode $id does not exist!");
				$this->id = -1;
				return;
			} else {
				$row = $dbConn->fetch($result);
				$this->id = $id;
				$this->key = $row['key'];
				$this->expiryTime = $row['expiry'];
			}
		}
		//Load by code
		elseif ($key != NULL) {
			$result = $dbConn->query("SELECT id,expiry FROM `keys` WHERE `key`='".$key."' LIMIT 1");
			if ($dbConn->rows($result) == 0) {
				//Key Doesn't exist.
				trigger_error("Could not find a keycode with code ".$key.".");
				$this->id = -1;
				return;
			} else {
				$row = $dbConn->fetch($result);
				$this->id = $row['id'];
				$this->key = $key;
				$this->expiryTime = $row['expiry'];
			}
		}
		
		//Load Actions
		if ($this->id != -1) {
			$result = $dbConn->query("SELECT action FROM keys_action WHERE key_id = ".$this->id);
			while ($row = $dbConn->fetch($result)) {
				$this->actions[] = $row['action'];
			}
			
			//Load Expiry Actions
			$result = $dbConn->query("SELECT action FROM keys_expiry WHERE key_id = ".$this->id);
			while ($row = $dbConn->fetch($result)) {
				$this->expiryActions[] = $row['action'];
			}
		}
		
	} //End Constructor
	
	
	/**
    * Returns a human readable description about the action of the keycode
    * @since 1.0
    * @param $id. No parameters
    * @return No return value.
    */
	function getFriendlyDesc() {
		$response = "";
		foreach ($this->actions as $action) {
			//Iterate through all known actions
			 if (strstr($action,"ActivateAccount_") !== false) {
				 //Activates a user account
				 $response .= "Activates user account #".str_replace("ActivateAccount_","",$action). ", ";
				 
			 } elseif (strstr($action,"BasketTotal_") !== false) {
				 //Alters the basket's total
				$response .= "Adjusts the basket total by ".str_replace("BasketTotal_","",$action).", ";
				
			 } elseif (preg_match("/Item[0-9]*Price_/",$action)) {
				 //Sets the price of a specific item
				 $item = preg_replace("/Item([0-9]*)Price_(.*)/","$1",$action);
				 $response .= "Item #$item is £".str_replace("Item".$item."Price_","",$action).", ";
			 }
		}
		
		//Remove last comma and space & return value
		return substr($response,0,strlen($response)-2);
	}
	
	
}
?>