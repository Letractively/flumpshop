<?php
require_once dirname(__FILE__)."/../header.inc.php";

if ($_POST['password'] != $_POST['password2']) {
	echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Oops! Your administrator passwords don't match. Please go back and try again!</div>";
	require_once dirname(__FILE__)."/../footer.inc.php";
	exit;
}

$_SESSION['config']->setNode("messages","name",$_POST['name']);
$_SESSION['config']->setNode("messages","tagline",$_POST['tagline']);
$_SESSION['config']->setNode("messages","email",$_POST['email']);
$_SESSION['config']->setNode("messages","address",$_POST['address']);
$_SESSION['config']->setNode("site","password",md5($_POST['password']));

//Process next stage
echo "<script>window.location = '../stages/".getNextStage(3)."'</script>";
require_once dirname(__FILE__)."/../footer.inc.php";
?>