<?php
$USR_REQUIREMENT = "can_delete_categories";
require_once "../header.php";
?><h1>Category Archive</h1>
<p>This page is where categories go when they are deleted, because, in reality, they are not. All categories remain in the database permanently after creation, but are removed from all lists, and sent here. This is very useful, both for historical reasons, and for the fact a category may occasionally be deleted unintentionally. The categories on this page are sorted alphabetically.</p>
<table>
	<tr>
		<th>Category ID</th>
		<th>Category Name</th>
	</tr>
<?php
$result = $dbConn->query("SELECT id FROM `category` WHERE enabled=0 ORDER BY name ASC");

while ($row = $dbConn->fetch($result)) {
	$category = new Category($row['id']);
	echo "<tr><td>".$category->getID()."</td><td>".$category->getName()."</td></tr>";
}
?>
</table>
</body></html>