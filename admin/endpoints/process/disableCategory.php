<?php
require_once dirname(__FILE__)."/../../../preload.php";
if (!isset($_SESSION['adminAuth']) || !$_SESSION['adminAuth']) die($config->getNode('messages','adminDenied'));

$id = intval($_GET['id']);

$dbConn->query("UPDATE `category` SET enabled=0 WHERE id='$id' LIMIT 1");
?>Category Disabled. It will no longer appear in any public category lists.