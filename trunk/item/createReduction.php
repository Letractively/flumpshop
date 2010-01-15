<?php
require_once dirname(__FILE__)."/../preload.php";
if (!isset($_SESSION['adminAuth']) || $_SESSION['adminAuth'] == false) die($config->getNode('messages','adminDenied'));

$id = $_POST['itemID'];
$tempPrice = $_POST['reducedPrice'];
$startDate = $_POST['validDate'];
if (strtotime($startDate) < time()) $startDate = $dbConn->time(); else $startDate = $dbConn->time($startDate);
$endDate = $_POST['expiresDate'];
$endDate = $dbConn->time($endDate);

$item = new Item($id);
$item->setReduction($tempPrice,$startDate,$endDate);
header("Location: ".$_SERVER['HTTP_REFERER']."?reductionHappened");
?>