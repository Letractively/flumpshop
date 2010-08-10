<?php
class Feature {
	
	var $id;
	var $name;
	var $datatype;
	var $default;
	
	function Feature($id) {
		$this->id = intval($id);
		global $dbConn;
		//Load Feature
		$result = $dbConn->query("SELECT * FROM `compare_features` WHERE id='".$this->id."' LIMIT 1");
		if ($dbConn->rows($result) == 0) {
			trigger_error("Failed to load feature #$id - Feature does not exist in database.");
		} else {
			$row = $dbConn->fetch($result);
			$this->name = $row['feature_name'];
			$this->datatype = $row['data_type'];
			$this->default = $row['default_value'];
		}
	}
	
	function getID() {
		return $this->id;
	}
	
	function getName() {
		return $this->name;
	}
	
	function getDataType() {
		return $this->datatype;
	}
	
	function getDefault() {
		return $this->default;
	}
	
	function getUnits() {
		global $dbConn;
		$result = $dbConn->query("SELECT unit FROM `feature_units` WHERE feature_id = ".$this->id." ORDER BY multiple ASC");
		
		$return = array();
		
		while ($row = $dbConn->fetch($result)) {
			$return[] = $row['unit'];
		}
		return $return;
	}
	
	function parseValue($input) {
		if ($this->datatype == "number") {
			//Numbers require unit parsing
			global $dbConn;
			$result = $dbConn->query("SELECT * FROM `feature_units` WHERE feature_id = ".$this->id." ORDER BY multiple DESC");
			if ($dbConn->rows($result) == 0) {
				return $input;
			} else {
				while ($row = $dbConn->fetch($result)) {
					if ($row['multiple'] == 0) {
						//Can't divide by 0
						trigger_error("Math Err: Cannot divide by multiple of 0.");
						continue;
					}
					if ($input/$row['multiple'] >= 1) {
						//Unit applies to this number (Others will too, but this is the highest due to sorting in the query)
						return round($input/$row['multiple'],2)." ".$row['unit'];
					}
				}
				//No units found. Return.
				return $input;
			}
		} else {
			return $input;
		}
	}
	
}
?>