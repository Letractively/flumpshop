<?php
require_once dirname(__FILE__)."/../header.php";

$count = $dbConn->rows($dbConn->query("SELECT id FROM `products`"));
$perPage = $config->getNode("pagination","editItemsPerPage");
if (isset($_GET['page'])) $page = $_GET['page']; else $page = 1;

$result = $dbConn->query("SELECT id FROM `products` ORDER BY name ASC LIMIT ".(($page-1)*$perPage).",$perPage");
echo "<div class='ui-widget-header'>Edit Item</div><div class='ui-widget-content'><p>Choose an item below to edit. You will then be taken to the item page, where you can click any value to change it.</p>";
while ($row = $dbConn->fetch($result)) {
	$item = new Item($row['id']);
	echo "<a href='".$item->getModifyURL()."'>".$item->getName()."</a><br />";
}

$paginator = new Paginator();
echo $paginator->paginate($page,$perPage,$count,"editItems.php");
?></div></div></body></html>