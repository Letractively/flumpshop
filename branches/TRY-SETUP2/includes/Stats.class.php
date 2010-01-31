<?php
class Stats {
	var $stats = array();
	
	function cacheAll() {
		//Used for exportation
		global $dbConn;
		$result = $dbConn->query("SELECT * FROM `stats`");
		while ($row = $dbConn->fetch($result)) {
			$this->stats[$row['key']] = $row['value'];
		}
	}
	
	function getStat($key) {
		global $dbConn;
		//Cache result to prevent multiple queries
		if (!isset($this->stats[$key])) {
			$result = $dbConn->fetch($dbConn->query("SELECT value FROM `stats` WHERE `key`='$key' LIMIT 1"));
			$this->stats[$key] = $result['value'];
		}
		return $this->stats[$key];
	}
	
	function setStat($key,$value) {
		global $dbConn;
		$this->stats[$key] = $value;
		$result = $dbConn->query("SELECT id FROM `stats` WHERE `key`='$key' LIMIT 1");
		if ($dbConn->rows($result) == 0) {
			return $dbConn->query("INSERT INTO `stats` (`key`,value) VALUES ('$key','$value')");
		} else {
			$id = $dbConn->fetch($result);
			return $dbConn->query("UPDATE `stats` SET value='$value' WHERE id=".$id['id']." LIMIT 1");
		}
	}
	
	function import() {
		global $dbConn;
		foreach (array_keys($this->stats) as $key) {
			if (!$this->setStat($key,$this->stats[$key])) {
				echo $key;
				return false;
			}
		}
		return true;
	}
	
	function incStat($key) {
		$newVal = intval($this->getStat($key));
		$newVal++;
		return $this->setStat($key,$newVal);
	}
	
	function getHighestStat($filter = "%", $int = 1) {
		global $dbConn;
		$result = $dbConn->query("SELECT `key` FROM `stats` WHERE `key` LIKE '$filter' ORDER BY value DESC LIMIT 0,$int");
		if ($int == 1) {
			$result = $dbConn->fetch($result);
			return $result['key'];
		} else {
			$return = array();
			while ($row = $dbConn->fetch($result)) {
				$return[] = $row['key'];
			}
			return $return;
		}
	}
}
?>