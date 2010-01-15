<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../../preload.php";

$dbConn->query("UPDATE `bugs` SET `resolved` = 1 WHERE id = ".$_GET['id']." LIMIT 1");

header("Location: ../advanced/bugs.php");
?>