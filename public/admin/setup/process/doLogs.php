<?php
require_once dirname(__FILE__)."/../header.inc.php";

$tree = "logs";

$_SESSION['config']->falseify($tree);

foreach (array_keys($_POST) as $key) {
	if ($_SESSION['config']->getNode($tree,$key) === false) {
		$_SESSION['config']->setNode($tree,$key,true);
	} else {
		$_SESSION['config']->setNode($tree,$key,$_POST[$key]);
	}
}

//Process next stage
echo "<script>window.location = '../stages/".getNextStage(12)."'</script>";
require_once dirname(__FILE__)."/../footer.inc.php";
?>