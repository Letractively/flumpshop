<?php
class News {
	var $id;
	var $title;
	var $body;
	var $timestamp;
	var $poster;
	
	function News($id) {
		global $dbConn;
		$this->id = $id;
		$result = $dbConn->query("SELECT * FROM `news` WHERE id=$this->id LIMIT 1");
		$result = $dbConn->fetch($result);
		$this->title = $result['title'];
		$this->body = $result['body'];
		$this->timestamp = $result['timestamp'];
		$this->poster = $result['poster'];
	}
	
	function getID() {
		return $this->id;
	}
	
	function getURL() {
		return "javascript:alert('This feature has not been implemented yet.');";
	}
	
	function import() {
		global $dbConn;
		if ($dbConn->rows($dbConn->query("SELECT * FROM `news` WHERE id=".$this->id." LIMIT 1"))) {
			$query = "UPDATE `news` SET title='".str_replace("'","''",$this->title)."', body='".str_replace("'","''",$this->body)."', timestamp='".$this->timestamp."', poster='$this->poster' WHERE id=".$this->id." LIMIT 1";
		} else {
			$query = "INSERT INTO `news` (id,title,body,timestamp,poster) VALUES ($this->id,'".str_replace("'","''",$this->title)."','".str_replace("'","''",$this->body)."','$this->timestamp','$this->poster')";
		}
		return $dbConn->query($query);
	}
}
?>