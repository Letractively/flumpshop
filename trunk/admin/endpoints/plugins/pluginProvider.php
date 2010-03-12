<?php
//Just loads resources then passes to the plugin file
if (!isset($_GET['noHeader'])) require_once "../header.php"; else require_once "../../../preload.php";
if (!file_exists($config->getNode("paths","offlineDir")."/plugins/".$_GET['mod']."/endpoints/".$_GET['page'].".php")) {
	echo "<h1>Plugin Error</h1><p>Flumpshop couldn't find the Administrator Endpoint ".$_GET['page']." for module ".$_GET['mod'].". Please contact the plugin author for help.";
	exit;
}
require_once $config->getNode("paths","offlineDir")."/plugins/".$_GET['mod']."/endpoints/".$_GET['page'].".php";
?>