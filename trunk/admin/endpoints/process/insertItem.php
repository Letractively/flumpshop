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
	for ($i = 0; $i < $_POST['number']; $i++) {
		if ($dbConn->query("INSERT INTO `products` (name,description,price,stock,category,weight) VALUES ('$name','$description','$price','$stock','$category','$weight')")) {
			$id = $dbConn->insert_id();
			echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Product Added to database with ID #".$id."</div>";
		} else {
			echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Failed to add item to database.</div>";
		}
	}
}

if (isset($_FILES['image'])) {
	$item = new Item($id);
	$error = !$item->saveImage($_FILES['image']['tmp_name'],$_FILES['image']['type']);
	if ($error) {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-info'></span>The image file you uploaded is not supported.</div>";
	}
}

unset($item); //Crashes in include if already defined (Don't know why)

include dirname(__FILE__)."/../create/newItem.php";
?>