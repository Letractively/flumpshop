<?php
class Config {
	var $name = "Default";
	var $data = array();
	var $namespaces = array();
	var $debug = false;
	var $editable = true;
	var $change = false;
	
	function Config($name = "Default",$debug = false) {
		$this->name = $name;
		$this->debug = $debug;
	}
	
	function __destruct() {
		global $_SETUP;
		//Save the configuration changes if the setup wizard is not running and a non-temp var has been changed
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
	
	function setNode($treeName,$nodeName,$nodeVal,$friendName = "") {
		//Only allow changing of temp vars of he file isn't editable
		if (!$this->editable && $treeName != "temp") return false;
		//If the Friendly name hasn't been defined yet, do it now
		if (!isset($this->namespaces[$treeName]['varsNames'][$nodeName])) {$this->namespaces[$treeName]['varsNames'][$nodeName] = $friendName;}
		//Set Value
		$this->data[$treeName][$nodeName] = $nodeVal;
		//Report change if not Temp
		if ($treeName != "temp") $this->change = true;
		//Print the value (change true/false to 1/0)
		if (is_bool($nodeVal)) $nodeVal = intval($nodeVal);
		$this->printDebug("Set $nodeName to $nodeVal");
		//Success
		return true;
	}
	
	function getNode($treeName,$nodeName) {
		//Returns the value of a node, or NULL if it doesn't exist
		if ($this->isNode($treeName,$nodeName)) {
			$value = $this->data[$treeName][$nodeName];
			return $value;
		} else {
			return NULL;
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