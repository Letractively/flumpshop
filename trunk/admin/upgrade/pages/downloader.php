<?php
require_once dirname(__FILE__)."/../../../preload.php";
copy("http://flumpshop.googlecode.com/files/".$_GET['file'],$config->getNode('paths','offlineDir')."/".$_GET['file']);
echo "Download Complete. <a href='notices.php'>Click here to continue.</a>";
?>