<?php
class Upgrade {
	var $newVersion;
	var $notices;
	var $confUpdate;
	var $sqlUpdate;
	
	function Upgrade() {
		$this->confUpdate = array();
	}
	
	function newVersion() {
		return $this->newVersion;
	}
	
	function setVersion($str) {
		$this->newVersion = $str;
	}
	
	function getNotes() {
		global $config;
		$notices = $this->notices;
		if (!is_writable($config->getNode("paths","path")."/conf.txt")) {
			$notices .= "<div class='ui-state-highlight'><span class='ui-icon ui-icon-locked'></span>Ensure PHP has write access to the main directory of Flumpshop (".$config->getNode("paths","path").").</div>";
		}
		return $notices;
	}
	
	function setNotes($str) {
		$this->notices = $str;
	}
	
	function getConfUpdate() {
		return $this->confUpdate;
	}
	
	function setConfUpdate($str) {
		$this->confUpdate = $str;
	}
	
	function setSQL($str) {
		$this->sqlUpdate = $str;
	}
	
	function getSQL() {
		return $this->sqlUpdate;
	}
}
?>