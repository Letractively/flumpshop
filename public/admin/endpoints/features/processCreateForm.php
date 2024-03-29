<?php
$USR_REQUIREMENT = "can_add_features";
require_once dirname(__FILE__)."/../header.php";

//Get submitted data
$name = htmlentities($_POST['name'],ENT_QUOTES);
$datatype = htmlentities($_POST['datatype'],ENT_QUOTES);
$default = htmlentities($_POST['default'],ENT_QUOTES);

if ($dbConn->query("INSERT INTO `compare_features` (feature_name,data_type,default_value) VALUES ('$name','$datatype','$default')")) {
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Feature $name has been added succesfully.</div>";
	//Create Units
	for ($i = 0; isset($_POST['unitMultiple'.$i]);$i++) {
		$newId = $dbConn->insert_id();
		$dbConn->query("INSERT INTO feature_units (feature_id,multiple,unit) VALUES ($newId,".intval($_POST['unitMultiple'.$i]).",'".str_replace("'","''",$_POST['unitSuffix'.$i])."')");
	}
} else {
	echo "<div class='ui-state-alert'><span class='ui-icon ui-icon-alert'></span>Failed to create feature attribute. Please contact your system administrator.</div>";
}

require_once "../features/createForm.php";
?>