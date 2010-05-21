<?php
require_once "../header.php";
?><h1>Manage Features</h1>
<p>This is the place to start when it comes to creating advanced product databases. Here, you can create <em>Features</em>, special information that applies to all products in a category, which provides a better way of organising them, and allows visitors to your website to find and understand information more easily.</p>
<?php if (acpusr_validate("can_add_features")) echo '<a href="../features/createForm.php" style="color:#000;">Add Feature</a>';

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