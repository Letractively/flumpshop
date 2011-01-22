<?php
require_once dirname(__FILE__)."/../header.inc.php";

$_SESSION['config']->setNode("paypal","enabled",isset($_POST['enabled']));
$_SESSION['config']->setNode("paypal","uname",$_POST['uname']);
$_SESSION['config']->setNode("paypal","pass",$_POST['pass']);
$_SESSION['config']->setNode("paypal","apiKey",$_POST['apiKey']);

//Process next stage
echo "<script>window.location = '../stages/".getNextStage(7)."'</script>";
require_once dirname(__FILE__)."/../footer.inc.php";
?>