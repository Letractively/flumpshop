<?php
//die();
require_once "../includes/vars.inc.php";

header("Content-Type: text/javascript");
echo file_get_contents($config->getNode('paths','offlineDir')."/plugins/".$_GET['mod']."/includes/".$_GET['file'].".js");
?>