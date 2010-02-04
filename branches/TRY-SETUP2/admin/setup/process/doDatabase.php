<?php
require_once dirname(__FILE__)."/../header.inc.php";

$_SESSION['config']->setNode("database","type",$_POST['type']);
$_SESSION['config']->setNode("database","address",$_POST['address']);
$_SESSION['config']->setNode("database","port",$_POST['port']);
$_SESSION['config']->setNode("database","uname",$_POST['uname']);
$_SESSION['config']->setNode("database","password",$_POST['password']);
$_SESSION['config']->setNode("database","name",$_POST['name']);

echo "<script>parent.leftFrame.window.location = '../?frame=leftFrame&p=2.3'; window.location = '../stages/about.php';</script>";
require_once dirname(__FILE__)."/../footer.inc.php";
?>