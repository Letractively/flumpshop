<?php
$USR_REQUIREMENT = "can_edit_categories";

require_once dirname(__FILE__)."/../header.php";

$id = intval($_POST['id']);
$name = str_replace("'","''",$_POST['name']);
$description = str_replace("'","''",$_POST['description']);
$parent = intval($_POST['parent']);
$searchable = isset($_POST['searchable']);

$status = "";
//Update main category row
if ($name == "") {
	$status .= "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>'Name' is a required field</div>";
} else {
	if ($dbConn->query("UPDATE `category`
			SET name='$name', description='$description', parent='$parent', searchable='$searchable'
			WHERE id='$id' LIMIT 1")) {
		$status .= '<div class="ui-state-highlight"><span class="ui-icon ui-icon-circle-check"></span>Category Updated</div>';
	} else {
		$status .= '<div class="ui-state-error"><span class="ui-icon ui-icon-alert"></span>Failed to save category changes</div>';
	}
}
//Add new feature references
$i = 0;
while (isset($_POST['feature_'.$i])) {
	$newFeature = $_POST['feature_'.$i];
	if ($dbConn->query("INSERT INTO `category_feature` (category_id,feature_id) VALUES ($id,$newFeature)")) {
			$status .= '<div class="ui-state-highlight"><span class="ui-icon ui-icon-circle-check"></span>Feature Assigned</div>';
	} else {
		$status .= '<div class="ui-state-error"><span class="ui-icon ui-icon-alert"></span>Failed to assign new feature</div>';
	}
	$i++;
}
header ("Location: ../switchboard/categories.php?msg=$status");
?>