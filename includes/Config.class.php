<?php

/**
*  Provides global logic and storage for sitewite data
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
*  @Name        : Config.class.php
*  @Version     : 1.0
*  @author		: Lloyd Wallis <lloyd@theflump.com>
*  @copyright	: Copyright (c) 2009-2010, Lloyd Wallis
*/


class Config {
	var $name = "Default";
	var $data = array();
	var $namespaces = array();
	var $debug = false;
	var $editable = true;
	var $change = false;
	
	/**
    * Config constructor.
    * @since 1.0
    * @param string $name Optional. A name to assign to the configuration object. Currently not used. Default "Default".
	* @param bool $debug Optional. Whether to output debugging data when executiong functions. Default false.
    * @return void No return value.
    */
	function Config($name = "Default",$debug = false) {
		$this->name = $name;
		$this->debug = $debug;
	}
	
	function __destruct() {
		global $_SETUP;
		//Save the configuration changes if the setup wizard is not running and a non-temp var has been changed
		$this->clearCache();
		if ((!isset($_SETUP) or $_SETUP === false) and $this->change) {
			//Delete temp tree
			$this->removeTree("temp");
			file_put_contents($this->getNode('paths','offlineDir')."/conf.txt",serialize($this));
			file_put_contents($this->getNode('paths','path')."/conf.txt",$this->getNode('paths','offlineDir')."/conf.txt");
		}
	}
	
	function import() {
		//Imported. Attempt to learn offline directory
		$this->setNode('paths',"offlineDir",preg_replace('/\/conf\.txt$/i','',file_get_contents(dirname(__FILE__)."/conf.txt")));
		$this->setNode('paths','path',dirname(__FILE__));
	}
	
	function addTree($treeName,$friendName = "") {
		//Create a new tree of variables
		//Fail if the conf.txt isn't editable
		if (!$this->editable) return false;
		//Enter Friendly Name
		$this->namespaces[$treeName]['friendName'] = $friendName;
		//Create Tree Array
		$this->data[$treeName] = array();
		//Report Change so that it's saved
		$this->change = true;
		//Report Result
		$this->printDebug("Created Tree $treeName");
		return true;
	}
	
	function removeTree($treeName) {
		//Deletes a tree
		//Fail if conf.txt isn't editable
		if (!$this->editable and $treeName != "temp") return false;
		//Remove friendly names
		unset($this->namespaces[$treeName]);
		//Remove Data
		unset($this->data[$treeName]);
		//Mark as changed
		if ($treeName != "temp") $this->change = true;
		//Report Result
		$this->printDebug("Removed Tree $treeName");
		return true;
	}
	
	function falseify($tree = NULL) {
		//Set all booleans to false as unchecked fields aren't sent in forms
		//Check conf.txt is editable
		if (!$this->editable) return false;
		//Null wipes the entire object (all trees)
		if ($tree == NULL) {
			foreach ($this->getTrees() as $tree) {
				foreach ($this->getNodes($tree) as $node) {
					if ($this->getNode($tree,$node) === true) {
						$this->setNode($tree,$node,false);
					}
				}
			}
		} else {
			foreach ($this->getNodes($tree) as $node) {
				if ($this->getNode($tree,$node) === true) {
					$this->setNode($tree,$node,false);
				}
			}
		}
		//Report Change
		$this->change = true;
		return true;
	}
	
	function getTrees() {
		//Returns a list of all variable trees in the object
		return array_keys($this->namespaces);
	}
	
	function clearCache() {
		//Updates cache data to remove expired information
		$time = time();
		//Only Check once an hour
		if (isset($this->namespaces['cache']['nextCheck']) and $this->namespaces['cache']['nextCheck'] > $time) return;
		
		$this->change = true;
		debug_message("Clearing Cache...");
		global $dbConn;
		
		if (is_object($dbConn) && isset($this->namespaces['cache']['expirations'])) { //Don't trigger error if nothing's been cached, ever, or the Database doesn't happen
			foreach ($this->namespaces['cache']['expirations'] as $nodeName => $timeout) {
				if ($timeout < $time) {
					debug_message("Removing $nodeName from cache...");
					$dbConn->query("DELETE FROM `cache` WHERE id='".$this->data['cache'][$nodeName]."' LIMIT 1");
					unset($this->data['cache'][$nodeName],$this->namespaces['cache']['expirations'][$nodeName]);
				}
			}
		}
		
		//Schedule next check
		$this->namespaces['cache']['nextCheck'] = $time+3600;
	}
	
	function setNode($treeName,$nodeName,$nodeVal,$friendName = "",$cacheTimeout = 3) {
		//Only allow changing of temp vars of he file isn't editable
		if (!$this->editable && $treeName != "temp") return false;
		//If the Friendly name hasn't been defined yet, do it now
		if (!isset($this->namespaces[$treeName]['varsNames'][$nodeName])) {$this->namespaces[$treeName]['varsNames'][$nodeName] = $friendName;}
		//Report change if not Temp
		if ($treeName != "temp") $this->change = true;
		//Magic: Store an expiration time if the tree is cache (default 1h)
		//And actually store the data in the database, and set the value to the ID
		if ($treeName == "cache") {
			debug_message("Storing cache data");
			global $dbConn;
			
			$this->namespaces['cache']['expirations'][$nodeName] = time()+$cacheTimeout; //Timeout
			debug_message("Timeout set to ".$this->namespaces['cache']['expirations'][$nodeName]);
			
			if (!$this->isNode('cache',$nodeName)) { //Doesn't Exist Yet
				debug_message("New cache Data");
				$dbConn->query("INSERT INTO `cache` (nodeName,cache) VALUES ('".$nodeName."','".str_replace("'","''",$nodeVal)."')");
				$this->data['cache'][$nodeName] = $dbConn->insert_id();
				debug_message("New cache ID: ".$dbConn->insert_id());
			} else { //Already Exists
				debug_message("Existing cache Data");
				$dbConn->query("UPDATE `cache` SET cache = '".str_replace("'","''",$nodeVal)."' WHERE id='".$this->getNode('cache',$nodeVal)."' LIMIT 1");
			}
		} else { //Not cache
			//Set Value
			$this->data[$treeName][$nodeName] = $nodeVal;
		}
		//Print the value (change true/false to 1/0)
		if (is_bool($nodeVal)) $nodeVal = intval($nodeVal);
		$this->printDebug("Set $nodeName to $nodeVal");
		//Success
		return true;
	}
	
	function getNode($treeName,$nodeName) {
		//Returns the value of a node, or NULL if it doesn't exist
		if ($treeName == "cache") {
			//Grab from DB
			global $dbConn;
			$result = $dbConn->query("SELECT cache FROM `cache` WHERE nodeName='$nodeName' LIMIT 1");
			$row = $dbConn->fetch($result);
			unset($result);
			return $row['cache'];
		} else {
			if ($this->isNode($treeName,$nodeName)) {
				$value = $this->data[$treeName][$nodeName];
				return $value;
			} else {
				return NULL;
			}
		}
	}
	
	function isNode($treeName,$nodeName) {
		//Checks if the node exists
		return isset($this->data[$treeName][$nodeName]);
	}
	
	function isTree($treeName) {
		//Checks whether a tree exists
		return isset($this->data[$treeName]);
	}
	
	function getFriendName($treeName, $nodeName = NULL) {
		//Returns the friendly (human-readable) name of the node or tree
		debug_message("Getting Friendly Name for $treeName|$nodeName");
		if ($nodeName != NULL) {
			return $this->namespaces[$treeName]['varsNames'][$nodeName];
		} else {
			return $this->namespaces[$treeName]['friendName'];
		}
	}
	
	function getNodes($treeName) {
		//Returns a list of nodes in the specified tree
		return array_keys($this->data[$treeName]);
	}
	
	function printDebug($msg) {
		//Deprecated - Used to use custom function, now just calls main routine
		debug_message($msg);
	}
	
	function setEditable($bool) {
		//Mark whether the conf.txt can be edited
		$this->editable = $bool;
	}
	
	function getEditable() {
		//Check if the conf.txt can be edited
		return $this->editable;
	}
}
?>