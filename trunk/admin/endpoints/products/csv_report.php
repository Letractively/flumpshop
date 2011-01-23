<?php
require_once dirname(__FILE__)."/../../../preload.php";

if (!acpusr_validate("can_view_reports")) {
	header("HTTP/1.1 403 Forbidden");
	die($config->getNode("messages","adminDenied"));
}

header("Content-Type: text/csv");
header("Content-Disposition:attachment;filename=itemReport.csv");


//Get customisations
if (isset($_GET['custom'])) {
	$id = isset($_GET['id']);
	$sku = isset($_GET['sku']);
	$name = isset($_GET['name']);
	$cost = isset($_GET['cost']);
	$delivery = isset($_GET['delivery']);
	$price = isset($_GET['price']);
	$category = isset($_GET['category']);
	$sort1 = htmlentities($_GET['sort1'],ENT_QUOTES);
	$sort2 = htmlentities($_GET['sort2'],ENT_QUOTES);
} else {
	$id=true;
	$sku=true;
	$name=true;
	$cost=true;
	$delivery=true;
	$price=true;
	$category=false;
	$sort1="id";
	$sort2="";
}

if ($sort1 != "category") {
	//Create the sort string
	$sortString = $sort1;
	if ($sort2 != "") $sortString .= ",$sort2";
	$sortString .= " ASC";
	
	$result = $dbConn->query("SELECT id FROM `products` WHERE active=1 ORDER BY ".$sortString);
} else {
	if ($sort2 != "") $sortString= ",products.$sort2";
	//Categories are a little more complicated...
	$result = $dbConn->query("SELECT id FROM products
							 LEFT JOIN (SELECT item_category.itemid AS pid,category.name AS cname FROM item_category
							 			LEFT JOIN category
							 			ON item_category.catid = category.id) AS t1
							 ON products.id = t1.pid
							 ORDER BY t1.cname".$sortString);
}

if ($id) echo "ID,";
if ($sku) echo "SKU,";
if ($category) echo "Category,";
if ($name) echo "Description,";
if ($cost) echo "Cost to Produce,";
if ($delivery) echo "Delivery Cost,";
if ($price) echo "Selling Price";

while ($row = $dbConn->fetch($result)) {
	$item = new Item($row['id']);
	
	echo "\r\n";
	if ($id) echo $item->getID().",";
	if ($sku) echo '"'.str_replace('"','""',$item->getSKU()).'",';
	if ($category) {
		$cat = new Category($item->getCategory(0),"noparent");
		echo '"'.str_replace('"','""',html_entity_decode($cat->getName(),ENT_QUOTES)).'",';
	}
	if ($name) echo '"'.str_replace('"','""',html_entity_decode($item->getName(),ENT_QUOTES)).'",';
	if ($cost) echo $item->getFriendlyCost().",";
	if ($delivery) echo $item->getFriendlyDeliveryCost().",";
	if ($price) echo $item->getFriendlyPrice();
}
?>