<?php
require_once "../header.php";
?><h1>Features<sup>labs</sup></h1>
<p>Hey, this is just one of the new features that I'm learning how to do right now. It allows you to provide users with more useful information about a product, by specifically adding fields for each category to help them make their choice, called attributes. Below is a list of current attributes, and what they apply to.</p>
<p>Currently, this isn't feature complete, as comparison and item sorting are yet to be implemented.</p>
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
		echo "<td><ul>";
		if ($dbConn->rows($units) == 0) {
			echo "Not Applicable";
		} else {
			while ($unit = $dbConn->fetch($units)) {
				echo "<li>".$unit['unit']."(".$unit['multiple'].")</li>";
			}
		}
		echo "</ul></td>";
		//Fetch Categories
		$categories = $dbConn->query("SELECT category_id FROM `category_feature` WHERE feature_id = ".$feature->getID());
		$catnames = array(); //Sort alphamabetically
		while ($category = $dbConn->fetch($categories)) {
			$category = new Category($category['category_id']);
			$catnames[] = $category->getName();
		}
		echo "<td><ul>";
		sort($catnames);
		foreach ($catnames as $catname) {
			echo "<li>$catname</li>";
		}
		echo "</ul></td>";
	}
	?>
</table>
</body></html>