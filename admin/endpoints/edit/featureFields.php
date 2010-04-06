<?php
require_once "../../../preload.php";

$id = intval($_GET['id']);
$item = new Item(intval($_GET['itemid']));

if ($id == 0) {
	die("<p>There are no feature attributes for this category.</p>");
}

//Get category features from database
$result = $dbConn->query("SELECT feature_id FROM category_feature WHERE category_id='$id'");

if ($dbConn->rows($result) == 0) {
	//No features
	die("<p>There are no feature attributes for this category.</p>");
}
echo "<table>";
//Return each feature
while ($row = $dbConn->fetch($result)) {
	echo "<tr>";
	$feature = new Feature($row['feature_id']);
	
	echo "<td><label for='feature_".$feature->getID()."'>".$feature->getName().": </label></td>";
	echo "<td>";
	//Actual input field
	echo "<input type='text' class='ui-widget-content ui-state-default ".$feature->getDataType()."' name='feature_".$feature->getID()."' id='feature_".$feature->getID()."' value='".$item->itemFeatures[$feature->getID()]."' />";
	//Units field (if there's more than one unit)
	$units = $feature->getUnits();
	if (sizeof($units) == 1) {
		 echo $units[0];
	} elseif (sizeof($units) != 0) {
		echo "<select name='feature_".$feature->getID()."_unit' class='ui-widget-content ui-state-default'>";
		foreach ($units as $unit) {
			echo "<option value='$unit'>$unit</option>";
		}
		echo "</select>";
	}
	echo "</td></tr>";
}
echo "</table>";
?>