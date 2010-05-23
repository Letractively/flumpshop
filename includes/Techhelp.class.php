<?php
class Techhelp {
	var $id;
	var $title;
	var $body;
	var $timestamp;
	var $poster;
	
	function Techhelp($id) {
		global $dbConn;
		$this->id = $id;
		$result = $dbConn->query("SELECT * FROM `techhelp` WHERE id=$this->id LIMIT 1");
		$result = $dbConn->fetch($result);
		$this->title = ucwords($result['title']);
		$this->body = $result['body'];
		$this->timestamp = $result['timestamp'];
		$this->poster = $result['poster'];
	}
	
	function getID() {
		return $this->id;
	}
	
	function getTitle() {
		return $this->title;
	}
	
	function getURL() {
		global $config;
		return $config->getNode('paths','root')."/techhelp.php?id=".$this->id;
	}
	
	function import() {
		global $dbConn;
		if ($dbConn->rows($dbConn->query("SELECT * FROM `techhelp` WHERE id=".$this->id." LIMIT 1"))) {
			$query = "UPDATE `techhelp` SET title='".str_replace("'","''",$this->title)."', body='".str_replace("'","''",$this->body)."', timestamp='".$this->timestamp."', poster='".$this->poster."' WHERE id=".$this->id." LIMIT 1";
		} else {
			$query = "INSERT INTO `techhelp` (id,title,body,timestamp,poster) VALUES ($this->id,'".str_replace("'","''",$this->title)."','".str_replace("'","''",$this->body)."','$this->timestamp','$this->poster')";
		}
		return $dbConn->query($query);
	}
	
	function view() {
		$reply = "<div class='ui-widget'>";
		$reply .= "<div class='ui-widget-header'>".$this->title."</div>";
		$reply .= "<div class='ui-widget-content'>".$this->body."</div>";
		$reply .= "</div>";
		return $reply;
	}
}
?>