<?php
//Container for Exporting Keycodes
class Keycodes {
	var $keycodes = array();
	
	function Keycodes() {
		global $dbConn;
		$result = $dbConn->query("SELECT * FROM `keys`");
		while ($row = $dbConn->fetch($result)) {
			$keycodes[] = $row;
		}
	}
	
	function import() {
		global $dbConn;
		foreach ($this->keycodes as $keycode) {
			if ($dbConn->rows($dbConn->query("SELECT id FROM `keys` WHERE id='".$keycode['id']."' LIMIT 1")) == 1) {
				$qry = "UPDATE `keys` SET true=true";
				foreach ($keycode as $key => $value) {
					$qry .= ", `$key`='".str_replace("'","''",$value)."'";
				}
				$qry .= " WHERE id=".$keycode['id']." LIMIT 1";
			} else {
				$qry = "INSERT INTO `keys` (true";
				foreach (array_keys($keycode) as $key) {
					$qry .= ",".$key;
				}
				$qry .= ") VALUES (true";
				foreach ($keycode as $value) {
					$qry .= ",".$value;
				}
				$qry .=")";
			}
			if ($dbConn->query($qry)) {
				debug_message("Keycode #".$keycode['id']." imported successfully.");
			} else {
				echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>FAILED TO IMPORT KEYCODE #".$keycode['id']."</div>";
			}
		}
	}
}
?>