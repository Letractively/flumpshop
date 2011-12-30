<?php
require_once "../preload.php";

//Find the newsletter in the database
$result = $dbConn->query("SELECT (body) FROM `newsletters` WHERE newsletter_id=".intval($_GET['id'])." LIMIT 1");
//Return 404 if newsletter doesn't exist
if ($dbConn->rows($result) == 0) {
	header("Location: ../errors/404.php");
}
//Fetch newsletter
$row = $result->fetch_array();
echo html_entity_decode($row['body'],ENT_QUOTES);
?>