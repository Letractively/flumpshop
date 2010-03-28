<?php
$USR_REQUIREMENT = "can_edit_categories";

require_once dirname(__FILE__)."/../header.php";

$id = intval($_POST['catid']);
$name = str_replace("'","''",$_POST['name']);
$description = str_replace("'","''",$_POST['description']);
$parent = str_replace("'","''",$_POST['parent']);
$newFeature = intval($_POST['new_feature']);
//Update main category row
if ($name == "") {
	echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>'Name' is a required field</div>";
} else {
	if ($dbConn->query("UPDATE `category` SET name='$name', description='$description', parent='$parent' WHERE id='$id' LIMIT 1")) {
		echo '<div class="ui-state-highlight"><span class="ui-icon ui-icon-circle-check"></span>Category Updated</div>';
	} else {
		echo '<div class="ui-state-error"><span class="ui-icon ui-icon-alert"></span>Failed to save category changes</div>';
	}
}
//Add new feature reference
if ($newFeature >= 1) {
	if ($dbConn->query("INSERT INTO `category_feature` (category_id,feature_id) VALUES ($id,$newFeature)")) {
			echo '<div class="ui-state-highlight"><span class="ui-icon ui-icon-circle-check"></span>Feature Assigned</div>';
	} else {
		echo '<div class="ui-state-error"><span class="ui-icon ui-icon-alert"></span>Failed to assign new feature</div>';
	}
}
include dirname(__FILE__)."/../edit/editCategory.php";
?>