<?php
$USR_REQUIREMENT = "can_edit_pages";
require_once "../header.php";

function selectOptions($oldID) {
	$return = "";
	for ($i=(-20);$i<=20;$i++) {
		if ($i == $oldID) {
			$return .= '<option value='.$i.' selected="selected">'.$i.'</option>';
		} else {
			$return .= '<option value='.$i.'>'.$i.'</option>';
		}
	}
	return $return;
}
?><h1>Category Sorting</h1>
<p>The site navigation uses a <em>weight</em> system in order to set the order in which categories appear. Here, you can set the weight of each category. The heavier the category, the later the category will appear. Each weight should be a number between -20 and 20.</p>
<form action="processWeights.php" method="post">
<table class="ui-widget-content">
	<tr class="ui-widget-header">
		<th>Category Name</th>
		<th>Category Weight</th>
	</tr>
<?php
$result = $dbConn->query("SELECT id FROM `category` WHERE parent=0 AND enabled=1 ORDER BY weight ASC");

while ($row = $dbConn->fetch($result)) {
	$category = new Category($row['id']);
	echo '<tr><td>'.$category->getName().'</td><td style="text-align:center"><select name="'.$category->getID().'_weight">'.selectOptions($category->getWeight()).'</select></td></tr>';
}
?></table>
<input type="submit" value="Save" />
</form>
</body></html>