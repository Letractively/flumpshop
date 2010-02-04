<?php
require_once dirname(__FILE__)."/../header.inc.php";

if ($_POST['password'] != $_POST['password2']) {
	echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Oops! Your administrator passwords don't match. Please go back and try again!</div>";
	exit;
}

$_SESSION['config']->setNode("messages","name",$_POST['name']);
$_SESSION['config']->setNode("messages","tagline",$_POST['tagline']);
$_SESSION['config']->setNode("messages","email",$_POST['email']);
$_SESSION['config']->setNode("messages","address",$_POST['address']);
$_SESSION['config']->setNode("site","password",md5($_POST['password']));

if (isset($_SESSION['mode']) and $_SESSION['mode'] == "express") {
	echo "<script>parent.leftFrame.window.location = '../?frame=leftFrame&p=4.1'; window.location = '../stages/finish.php';</script>";
}
echo "<script>alert('We\'re working on the advanced features at the moment. Sorry.');//parent.leftFrame.window.location = '../?frame=leftFrame&p=2.3'; window.location = '../stages/about.php';</script>";
require_once dirname(__FILE__)."/../footer.inc.php";
?>