<?php
$USR_REQUIREMENT = "can_edit_pages";
require_once dirname(__FILE__)."/../header.php";

//Fetch the widgets order
$ordering = $_GET['wid'];

//Clear Ordering
$config->removeTree('homeCol1');
$config->removeTree('homeCol2');
$config->removeTree('homeCol3');

//Store new Ordering
$currentCol=1;
foreach ($ordering as $currentElement) {
	if ($currentElement == 0) {
		$currentCol++;
		$i = 0;
		$config->addTree('homeCol'.$currentCol,'HomeLayoutColumnStore'.$currentCol);
		continue;
	}
	$config->setNode('homeCol'.$currentCol,$i,$currentElement);
	$i++;
}
echo nl2br(print_r($config,true));
?>