<?php
$_PRINTDATA = false;
require_once dirname(__FILE__)."/../preload.php";
if (!$_SESSION['adminAuth']) die($config->getNode("messages","adminDenied"));

$error = false;

$item = new Item($_POST['id']);

$error = !$item->saveImage($_FILES['image']['tmp_name'],$_FILES['image']['type']);

if (!$error) header("Location: ".$_SERVER['HTTP_REFERER']."?imageHappened"); else {
	echo "<h1>Sorry!</h1>";
	echo "I can't work out what kind of image you uploaded. Can you try again in another format?<br />I can work with: JPEG, GIF, PNG, BMP";
}
?>