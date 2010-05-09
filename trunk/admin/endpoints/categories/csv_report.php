<?php
require_once dirname(__FILE__)."/../../../preload.php";

if (!acpusr_validate("can_view_reports")) {
	header("HTTP/1.1 403 Forbidden");
	die($config->getNode("messages","adminDenied"));
}

header("Content-Type: text/csv");
header("Content-Disposition:attachment;filename=categoryReport.csv");

//Get customisations
if (isset($_GET['custom'])) {
	$id = isset($_GET['id']);
	$name = isset($_GET['name']);
	$productCount = isset($_GET['productCount']);
	$parent = isset($_GET['parent']);
	$features = isset($_GET['features']);
	$sort1 = htmlentities($_GET['sort1'],ENT_QUOTES);
	$sort2 = htmlentities($_GET['sort2'],ENT_QUOTES);
} else {
	$id=true;
	$name=true;
	$productCount=true;
	$parent=false;
	$features=false;
	$sort1="id";
	$sort2="";
}

if ($sort1 != "productCount") {
	//Create the sort string
	$sortString = $sort1;
	if ($sort2 != "") $sortString .= ",$sort2";
	$sortString .= " ASC";
	
	$result = $dbConn->query("SELECT id FROM `category` WHERE enabled=1 ORDER BY ".$sortString);
} else {
	if ($sort2 != "") $sortString= ",category.$sort2";
	//Product count are a little more complicated... (and aren't implemented yet)
	$result = $dbConn->query("SELECT id FROM category ORDER BY id ASC");
}

if ($id) echo "ID,";
if ($name) echo "Description,";
if ($productCount) echo "Number of Products,";
if ($parent) echo "Parent,";
if ($features) echo "Features";

while ($row = $dbConn->fetch($result)) {
	$category = new Category($row['id']);
	
	echo "\r\n";
	if ($id) echo $category->getID().",";
	if ($name) echo '"'.str_replace('"','""',$category->getName()).'",';
	if ($productCount) echo $dbConn->rows($dbConn->query("SELECT id FROM products WHERE active=1 AND id IN (SELECT itemid as id FROM item_category WHERE catid=".$category->getID().")")).",";
	if ($parent) {
		$parent = new Category($category->getParent(),"noparent");
		echo '"'.str_replace('"','""',$parent->getName()).'",';
		unset($parent);
	}
	if ($features) echo '"Functionality not implemented.,"';
}
?>
