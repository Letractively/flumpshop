<?php
class Config {
	var $name = "Default";
	var $data = array();
	var $namespaces = array();
	var $debug = false;
	var $editable = true;
	
	function Config($name = "Default",$debug = false) {
		$this->name = $name;
		$this->debug = $debug;
	}
	
	function __destruct() {
		global $_SETUP;
		if (!isset($_SETUP) or $_SETUP === false) {
			file_put_contents($this->getNode('paths','offlineDir')."/conf.txt",serialize($this));
			file_put_contents($this->getNode('paths','path')."/conf.txt",$this->getNode('paths','offlineDir')."/conf.txt");
		}
	}
	
	function import() {
		//Imported. Attempt to learn offline directory
		$this->setNode('paths',"offlineDir",str_ireplace('/conf.txt','',file_get_contents(dirname(__FILE__)."/conf.txt")));
		$this->setNode('paths','path',dirname(__FILE__));
	}
	
	function addTree($treeName,$friendName = "") {
		if (!$this->editable) return false;
		$this->namespaces[$treeName]['friendName'] = $friendName;
		$this->data[$treeName] = array();
		$this->printDebug("Created Tree $treeName");
		return true;
	}
	
	function falseify($tree = NULL) {
		//Set all booleans to false as unchecked fields aren't sent
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
	}
	
	function getTrees() {
		return array_keys($this->namespaces);
	}
	
	function setNode($treeName,$nodeName,$nodeVal,$friendName = "") {
		if (!$this->editable && $treeName != "temp") return false;
		if (!isset($this->namespaces[$treeName]['varsNames'][$nodeName])) {$this->namespaces[$treeName]['varsNames'][$nodeName] = $friendName;}
		$this->data[$treeName][$nodeName] = $nodeVal;
		if (is_bool($nodeVal)) $nodeVal = intval($nodeVal);
		$this->printDebug("Set $nodeName to $nodeVal");
		return true;
	}
	
	function getNode($treeName,$nodeName) {
		if ($this->isNode($treeName,$nodeName)) {
			$value = $this->data[$treeName][$nodeName];
			return $value;
		} else {
			return NULL;
		}
	}
	
	function isNode($treeName,$nodeName) {
		return isset($this->data[$treeName][$nodeName]);
	}
	
	function getFriendName($treeName, $nodeName = NULL) {
		if ($nodeName != NULL) {
			return $this->namespaces[$treeName]['varsNames'][$nodeName];
		} else {
			return $this->namespaces[$treeName]['friendName'];
		}
	}
	
	function getNodes($treeName) {
		return array_keys($this->data[$treeName]);
	}
	
	function printDebug($msg) {
		debug_message($msg);
	}
	
	function setEditable($bool) {
		$this->editable = $bool;
	}
	
	function getEditable() {
		return $this->editable;
	}
}
?>