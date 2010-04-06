<?php
require_once "../header.php";
?><h1>Product Report</h1><?php
$result = $dbConn->query("SELECT id FROM `products` ORDER BY id ASC");
echo "<table border=1><tr><th>ID</th><th>SKU</th><th>Description</th><th>Cost to Produce</th><th>Delivery Cost</th><th>Selling Price</th></tr>";
while ($row = $dbConn->fetch($result)) {
	$item = new Item($row['id']);
	echo "<tr>";
	echo "<td>".$item->getID()."</td>";
	echo "<td>".$item->getSKU()."</td>";
	echo "<td>".$item->getName()."</td>";
	echo "<td>".$item->getFriendlyCost()."</td>";
	echo "<td>".$item->getFriendlyDeliveryCost()."</td>";
	echo "<td>".$item->getFriendlyPrice()."</td>";
	echo "</tr>";
}
echo "</table>";
?>