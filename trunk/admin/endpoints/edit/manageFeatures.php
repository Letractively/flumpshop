<?php
require_once "../header.php";
?><h1>Features<sup>labs</sup></h1>
<p>Hey, this is just one of the new features that I'm learning how to do right now. It allows you to provide users with more useful information about a product, by specifically adding fields for each category to help them make their choice, called attributes. Below is a list of current attributes, and what they apply to.</p>
<p>Currently, this isn't feature complete, as comparison is yet to be implemented.</p>
<p>Note: This page may take several minutes to finish loading, as pagination hasn't been added yet either.</p>
<a href="../create/newFeature.php" style="color:#000;">Add Feature</a>
<?php
//Get a list of features
$result = $dbConn->query("SELECT id FROM `compare_features` ORDER BY feature_name ASC");
?>
<table class="ui-widget">
	<tr class="ui-widget-header">
		<th>Attribute Name</th>
		<th>Attribute Type</th>
		<th>Default Value</th>
		<th>Units</th>
		<th>Categories</th>
	</tr>
	<?php
	while ($row = $dbConn->fetch($result)) {
		echo "<tr class='ui-widget-content'>";
		$feature = new Feature($row['id']);
		//Simple Data
		echo "<td>".$feature->getName()."</td><td>".ucwords($feature->getDataType())."</td><td>".$feature->getDefault()."</td>";
		//Fetch Units
		$units = $dbConn->query("SELECT multiple,unit FROM `feature_units` WHERE feature_id=".$feature->getID()." ORDER BY multiple ASC");
		echo "<td>";
		if ($dbConn->rows($units) == 0) {
			echo "None";
		} else {
			while ($unit = $dbConn->fetch($units)) {
				echo $unit['unit']."&nbsp;(x".$unit['multiple']."),";
			}
		}
		echo "</td>";
		//Fetch Categories
		$categories = $dbConn->query("SELECT id FROM `category` WHERE id IN (SELECT category_id FROM `category_feature` WHERE feature_id = ".$feature->getID().") ORDER BY name ASC");
		
		echo "<td>";
		
		while ($crow = $dbConn->fetch($categories)) {
			$category = new Category($crow['id'],"NOPARENT");
			echo $category->getName()."&nbsp;<span class='ui-icon ui-icon-circle-close' style='display:inline-block;float:none;margin-top:8px;cursor:pointer' onclick='removeCat(".$row['id'].",".$crow['id'].");'></span>,";
		}
		echo "</td>";
	}
	?>
</table>
<script type="text/javascript">
function removeCat(feature,category) {
	loader("Removing feature for category "+category+"...","Removing Feature");
	window.location="../process/removeFeatureCatRef.php?feature="+feature+"&category="+category;
}
</script>
</body></html>