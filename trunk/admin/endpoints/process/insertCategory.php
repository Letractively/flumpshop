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
		echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>";
		echo "Category Added to database with ID ".$dbConn->insert_id().". Next steps:<ol>";
		echo "<li>Add attributes to this category on the <a href='../edit/editCategory.php?id=".$dbConn->insert_id()."'>Edit Category page</a> to help users filter content</li>";
		echo "<li><a href='newItem.php'>Add New Products</a> and set the Category field to the new category.</li>";
		echo "<li><a href='../edit/editItemps.php'>Edit existing products</a> and add them to this category</li>";
		echo "</ol></div>";
	} else {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Failed to add category to database. Please contact you system administrator.</div>";
	}
}
include dirname(__FILE__)."/../create/newCategory.php";
?>