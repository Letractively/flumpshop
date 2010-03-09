<?php
// Needs more comments Lloyd - Jake
require_once dirname(__FILE__)."/../header.php";

$title = str_replace("'","''",$_POST['postTitle']);
$body = str_replace("'","''",$_POST['postContent']);

if ($dbConn->query("INSERT INTO `news` (title,body) VALUES ('$title','$body')")) {
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>The post has been added and is now visible on the home page.</div>";
} else {
	echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Failed to enter the post into the database.</div>";
}
include dirname(__FILE__)."/../create/newNews.php";
?>