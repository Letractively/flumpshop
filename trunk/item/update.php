<?php
$_PRINTDATA = false; //Disable Debug for AJAX Feature
$ajaxProvider = true;
require_once dirname(__FILE__)."/../preload.php";

if (!isset($_SESSION['adminAuth']) || !$_SESSION['adminAuth']) die($config->getNode('messages','adminDenied'));

$pid = $_GET['pid'];
if ($pid == "category") {
	$item = new Item($_POST['id']);
	$item->setCategory($_POST['newCat']);
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Category Updated</div>";
} else {
	$item = new Item($pid);
	
	switch ($_POST['id']) {
		case "itemTitle":
			$item->setName(str_replace("\\","",$_POST['value']));
			break;
		case "itemPrice":
			$item->setPrice($_POST['value']);
			break;
		case "itemDesc":
			$item->setDesc(str_replace(array("\\","\n"),"",nl2br($_POST['value'])));
			break;
		case "itemStock":
			$item->setStock($_POST['value']);
			break;
	}
	$item->save(); //PHP 4 Support (No destructor)
	echo str_replace(array("\\","\n"),"",nl2br($_POST['value']));
}
?>