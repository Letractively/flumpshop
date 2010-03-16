<?php
$USR_REQUIREMENT = "can_add_categories";

require_once dirname(__FILE__)."/../header.php";
$name = str_replace("'","''",$_POST['name']);
$description = str_replace("'","''",$_POST['description']);
$parent = str_replace("'","''",$_POST['parent']);
if ($name == "") {
	echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>'Name' is a required field.</div>";
} else {
	if ($dbConn->query("INSERT INTO `category` (name,description,parent) VALUES ('$name','$description','$parent')")) {
		echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Category Added to database with ID ".$dbConn->insert_id()."</div>";
	} else {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Failed to add category to database.</div>";
	}
}
include dirname(__FILE__)."/../create/newCategory.php";
?>