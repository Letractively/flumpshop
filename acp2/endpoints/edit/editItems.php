<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../../preload.php";

$count = $dbConn->rows($dbConn->query("SELECT id FROM `products`"));
$perPage = $config->getNode("pagination","editItemsPerPage");
if (isset($_GET['page'])) $page = $_GET['page']; else $page = 1;

$result = $dbConn->query("SELECT id FROM `products` ORDER BY name ASC LIMIT ".(($page-1)*$perPage).",$perPage");

while ($row = $dbConn->fetch($result)) {
	$item = new Item($row['id']);
	echo "<a href='".$item->getModifyURL()."'>".$item->getName()."</a><br />";
}

$paginator = new Paginator();
echo $paginator->paginate($page,$perPage,$count,"./endpoints/edit/editItems.php","adminContent");
?>