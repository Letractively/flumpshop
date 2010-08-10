<?php
$USR_REQUIREMENT = "can_add_products";

require_once dirname(__FILE__)."/../header.php";
loadClass('Feature');

$name = htmlentities($_POST['name'],ENT_QUOTES);
$description = nl2br(htmlentities($_POST['description'],ENT_QUOTES));
$price = str_replace("'","\'",$_POST['price']);
$stock = str_replace("'","\'",$_POST['stock']);
$weight = str_replace("'","\'",$_POST['weight']);

if ($dbConn->query("INSERT INTO `products` (name,description,price,stock,weight) VALUES ('$name','$description','$price','$stock','$weight')")) {
	$id = $dbConn->insert_id();
	//Product added, now add categories
	for ($i=0;isset($_POST['category_'.$i]);$i++) {
		if ($_POST['category_'.$i] != "")
		$dbConn->query("INSERT INTO `item_category` (itemid,catid) VALUES ($id,'".$_POST['category_'.$i]."')");
	}
	//Output success message
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Product Added to database with ID #".$id."</div>";
	//Upload Image
	if (isset($_FILES["image"])) {
		$item = new Item($id);
		$error = !$item->saveImage($_FILES["image"]['tmp_name'],$_FILES["imagi"]['type']);
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
	?><h1>Next Steps</h1>
	<p>Your product has been created, and is now on display in the storefront. What do you want to do next?</p>
	<ul>
		<li><a href="createSimple.php" onclick="loader('Please wait...','Loading Form');">Create another product</a></li>
		<li><a href="../switchboard/products.php" onclick="loader('Please wait...','Loading Product Manager');">Go back to the Manage Products page</a></li>
		<li><a href="../../../account/logout.php" target="_top">Logout of the Admin CP</a></li>
	</ul><?php
} else {
	//Query Exec Failed
	?><div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Failed to add item to database.</div>
	<p>Sorry, I couldn't create your product. Please try the following:</p>
	<ul>
		<li><a href='createSimple.php' onclick="loader('Please wait...','Loading Form');">Go back and create the product again</a></li>
		<li>Try again a little later</li>
		<li>Ask your supervisor or administrator for help</li>
	</ul><?php
}
?>