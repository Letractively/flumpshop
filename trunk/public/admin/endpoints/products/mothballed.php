<?php
$USR_REQUIREMENT = "can_delete_products";
require_once "../header.php";
?><h1>Product Archive</h1>
<p>This page is where products go when they are deleted, because, in reality, they are not. All products remain in the database permanently after creation, but are removed from all lists, and sent here. This is very useful, both for historical reasons, and for the fact a Product may occasionally be deleted unintentionally. The products on this page are sorted alphabetically.</p>
<table>
	<tr>
		<th>Product ID</th>
		<th>Product Name</th>
	</tr>
<?php
$result = $dbConn->query("SELECT id FROM `products` WHERE active=0 ORDER BY name ASC");

while ($row = $dbConn->fetch($result)) {
	$item = new Item($row['id']);
	echo "<tr><td>".$item->getID()."</td><td>".$item->getName()."</td></tr>";
}
?>
</table>
</body></html>