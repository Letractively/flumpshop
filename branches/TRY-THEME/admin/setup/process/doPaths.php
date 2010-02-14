<?php
require_once dirname(__FILE__)."/../header.inc.php";

if (!is_writable($_POST['offlineDir'])) {
	echo "<h1>Oops!</h1><p>As part of making this installation 'John-proof', I just ran an extra test. Unfortunately, it appears that the Offline Directory either doesn't exist, or I don't have necessary permissoins to write to it. <a href='javascript:history.go(-1)'>Click here</a> to go back and try again.";
	require_once dirname(__FILE__)."/../footer.inc.php";
	exit;
}

$_SESSION['config']->setNode("paths","root",$_POST['root']);
$_SESSION['config']->setNode("paths","secureRoot",$_POST['secureRoot']);
$_SESSION['config']->setNode("paths","path",$_POST['path']);
$_SESSION['config']->setNode("paths","offlineDir",$_POST['offlineDir']);
$_SESSION['config']->setNode("paths","logDir",$_POST['logDir']);

echo "<script>parent.leftFrame.window.location = '../?frame=leftFrame&p=2.2'; window.location = '../stages/database.php';</script>";
require_once dirname(__FILE__)."/../footer.inc.php";
?>