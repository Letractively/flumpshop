<?php
require_once dirname(__FILE__)."/../header.inc.php";

$_SESSION['config']->setNode("paths","root",$_POST['root']);
$_SESSION['config']->setNode("paths","secureRoot",$_POST['secureRoot']);
$_SESSION['config']->setNode("paths","path",$_POST['path']);
$_SESSION['config']->setNode("paths","offlineDir",$_POST['offlineDir']);
$_SESSION['config']->setNode("paths","logDir",$_POST['logDir']);

echo "<script>parent.leftFrame.window.location = '../?frame=leftFrame&p=2.2'; window.location = '../stages/database.php';</script>";
require_once dirname(__FILE__)."/../footer.inc.php";
?>