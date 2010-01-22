<?php
require_once dirname(__FILE__)."/../../includes/vars.inc.php";
copy("http://flumpshop.googlecode.com/svn/updater/".$_GET['file'],$config->getNode('paths','offlineDir')."/".$_GET['file']);
echo "Download Complete. <a href='javascript:history.go(0);'?>Click here to continue.</a>";
?>