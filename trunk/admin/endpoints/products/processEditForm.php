<?php
$USR_REQUIREMENT = "can_edit_products";

require_once dirname(__FILE__)."/../header.php";
$id = intval($_POST['id']);
$sku = htmlentities($_POST['sku'],ENT_QUOTES);
$name = htmlentities($_POST['name'],ENT_QUOTES);
$description = str_replace("'","\'",$_POST['description']);
$price = str_replace("'","\'",$_POST['price']);
$cost = str_replace("'","\'",$_POST['cost']);
$stock = str_replace("'","\'",$_POST['stock']);
$weight = str_replace("'","\'",$_POST['weight']);

for ($i = 0; $i < 1; $i++) {
	if ($dbConn->query("UPDATE `products` SET SKU='$sku',name='$name',description='$description',price='$price',cost='$cost',stock='$stock',weight='$weight' WHERE id=$id LIMIT 1")) {
		//Delete old category reference
		$dbConn->query("DELETE FROM `item_category` WHERE itemid=$id");
		//Product added, now add categories
		for ($i=0;isset($_POST['category_'.$i]);$i++) {
			if ($_POST['category_'.$i] != "")
			$dbConn->query("INSERT INTO `item_category` (itemid,catid) VALUES ($id,'".$_POST['category_'.$i]."')");
		}
		//Output success message
		$status = "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Product Updated!</div>";
		//Upload Image
		if (!empty($_FILES["image$i"])) {
			$item = new Item($id);
			$error = !$item->saveImage($_FILES["image$i"]['tmp_name'],$_FILES["image$i"]['type']);
			if ($error) {
				$status .= "<div class='ui-state-error'><span class='ui-icon ui-icon-info'></span>The image file you uploaded is not supported.</div>";
			}
		}
		
		//Add Features
		$features = array_keys($_POST);
		//Delete old ones
		$dbConn->query("DELETE FROM `item_feature_number` WHERE item_id=$id");
		$dbConn->query("DELETE FROM `item_feature_string` WHERE item_id=$id");
		$dbConn->query("DELETE FROM `item_feature_date` WHERE item_id=$id");
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
		$status = "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Failed to update item.</div>";
	}
}

if (isset($_POST['return'])) {
	header("Location: ".$_POST['return']);
	exit;
}

header("Location: ../switchboard/products.php?msg=".$status);
?>