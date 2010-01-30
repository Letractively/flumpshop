<?php
require_once dirname(__FILE__)."/../header.php";

$id = intval($_POST['catid']);
$name = str_replace("'","''",$_POST['name']);
$description = str_replace("'","''",$_POST['description']);
$parent = str_replace("'","''",$_POST['parent']);
if ($name == "") {
	echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>'Name' is a required field</div>";
} else {
	if ($dbConn->query("UPDATE `category` SET name='$name', description='$description', parent='$parent' WHERE id='$id' LIMIT 1")) {
		echo '<div class="ui-state-highlight"><span class="ui-icon ui-icon-circle-check"></span>Category Updated</div>';
	} else {
		echo '<div class="ui-state-error"><span class="ui-icon ui-icon-alert"></span>Failed to save changes</div>';
	}
}
include dirname(__FILE__)."/../edit/editCategory.php";
?>