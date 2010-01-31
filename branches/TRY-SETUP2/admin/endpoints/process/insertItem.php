<?php
require_once dirname(__FILE__)."/../header.php";
$name = htmlentities($_POST['name'],ENT_QUOTES);
$description = nl2br(htmlentities($_POST['description'],ENT_QUOTES));
$price = str_replace("'","\'",$_POST['price']);
$stock = str_replace("'","\'",$_POST['stock']);
$category = str_replace("'","\'",$_POST['category']);
$weight = str_replace("'","\'",$_POST['weight']);

if ($name == "" or $description == "") {
	echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>'Name' and 'Description' are required fields.</div>";
} else {
	if ($dbConn->query("INSERT INTO `products` (name,description,price,stock,category,weight) VALUES ('$name','$description','$price','$stock','$category','$weight')")) {
		echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Product Added to database with ID #".$dbConn->insert_id()."</div>";
	} else {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Failed to add item to database.</div>";
	}
}

include dirname(__FILE__)."/../create/newItem.php";
?>