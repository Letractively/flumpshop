<?php
define("PAGE_TYPE","plugin_endpoint");
require_once "header.php";

$mod = str_replace("../","",$_GET['mod']);
$page = str_replace("../","",$_GET['page']);

if (!file_exists($config->getNode("paths","offlineDir")."/plugins/".$mod."/public/".$page.".php")) {
	header("Location: errors/404.php");
} else {
	require_once $config->getNode("paths","offlineDir")."/plugins/".$mod."/public/".$page.".php";
}

require_once "footer.php";
?>