<?php
$requires_tier2 = true;
require_once "../header.php";

$dbConn->query("UPDATE `bugs` SET `resolved` = 1 WHERE id = ".$_GET['id']." LIMIT 1");

header("Location: ../advanced/bugs.php");
?>