<?php
$USR_REQUIREMENT = "can_add_categories";

require_once dirname(__FILE__)."/../header.php";
$name = str_replace("'","''",$_POST['name']);
$description = str_replace("'","''",$_POST['description']);
$parent = str_replace("'","''",$_POST['parent']);

$status = "";
if ($name == "") {
	$status .= "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>'Name' is a required field.</div>";
} else {
	if ($dbConn->query("INSERT INTO `category` (name,description,parent) VALUES ('$name','$description','$parent')")) {
		$status .= "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>";
		$status .= "Category Added to database with ID ".$dbConn->insert_id().". Next steps:<ol>";
		$status .= "<li><a href='../categories/createSimple.php'>Create another category</a></li>";
		$status .= "<li>Add features to this category on the <a href='../categories/editCategory.php?id=".$dbConn->insert_id()."'>Edit Category page</a> to help users filter content</li>";
		$status .= "<li><a href='../products/createSimple.php'>Add New Products</a> and set the Category field to the new category.</li>";
		$status .= "<li><a href='../products/editWizard.php'>Edit existing products</a> and add them to this category</li>";
		$status .= "</ol></div>";
	} else {
		$status .= "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Failed to add category to database. Please contact you system administrator.</div>";
	}
}
include dirname(__FILE__)."/../switchboard/categories.php?msg=$status";
?>