<?php
require_once dirname(__FILE__)."/../header.inc.php";

$tree = "messages";

foreach (array_keys($_POST) as $key) {
	$_SESSION['config']->setNode($tree,$key,$_POST[$key]);
}

//Process next stage
echo "<script>window.location = '../stages/".getNextStage(8)."'</script>";
require_once dirname(__FILE__)."/../footer.inc.php";
?>