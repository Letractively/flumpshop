<?php
$USR_REQUIREMENT = "can_add_products";

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
		if ($dbConn->query("INSERT INTO `products` (name,description,price,stock,weight) VALUES ('$name','$description','$price','$stock','$weight')")) {
			$id = $dbConn->insert_id();
			//Product added, now add categories
			$dbConn->query("INSERT INTO `item_category` (itemid,catid) VALUES ($id,$category)");
			//TODO: Frontend support for multiple categories before this block can be finished
			//Output success message
			echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Product Added to database with ID #".$id."</div>";
			//Upload Image
			if (isset($_FILES["image$i"])) {
				$item = new Item($id);
				$error = !$item->saveImage($_FILES["image$i"]['tmp_name'],$_FILES["image$i"]['type']);
				if ($error) {
					echo "<div class='ui-state-error'><span class='ui-icon ui-icon-info'></span>The image file you uploaded is not supported.</div>";
				}
			}
			
			//Add Features
			$features = array_keys($_POST);
			foreach ($features as $feature) {
				if (preg_match("/^feature_([0-9]*)$/",$feature)) {
					//Is a feature definition
					$feature_id = preg_replace("/^feature_([0-9]*)$/","$1",$feature);
					$feature_value = htmlentities($_POST[$feature],ENT_QUOTES);
					//Check for units
					if (isset($_POST[$feature."_unit"])) {
						$unit = htmlentities($_POST[$feature."_unit"],ENT_QUOTES);
						//Get multiplier from DB
						$result = $dbConn->query("SELECT multiple FROM `feature_units` WHERE feature_id=$feature_id AND unit='$unit' LIMIT 1");
						$row = $dbConn->fetch($result);
						$feature_value *= $row['multiple'];
					}
					//Get attribute type
					$featureObj = new Feature($feature_id);
					$dataType = $featureObj->getDataType();
					//Save attribute
					$dbConn->query("INSERT INTO `item_feature_$dataType` (item_id,feature_id,value) VALUES ($id,$feature_id,'$feature_value')");
				}
			}
		} else {
			//Query Exec Failed
			echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Failed to add item to database.</div>";
		}
	}
}

unset($item); //Crashes in include if already defined (Don't know why)

include dirname(__FILE__)."/../create/newItem.php";
?>