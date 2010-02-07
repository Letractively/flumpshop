<?php
require_once dirname(__FILE__)."/../header.inc.php";

$_SESSION['config']->setNode("site","enabled",isset($_POST['enabled']));
$_SESSION['config']->setNode("site","vat",$_POST['vat']);
$_SESSION['config']->setNode("site","shopMode",isset($_POST['shopMode']));
$_SESSION['config']->setNode("site","sendFeedback",isset($_POST['sendFeedback']));

//Process next stage
echo "<script>window.location = '../stages/".getNextStage(5)."'</script>";
require_once dirname(__FILE__)."/../footer.inc.php";
?>