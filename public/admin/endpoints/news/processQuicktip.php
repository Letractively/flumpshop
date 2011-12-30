<?php
$USR_REQUIREMENT = "can_post_news";

require_once dirname(__FILE__)."/../header.php";

$title = str_replace("'","''",$_POST['postTitle']);
$body = str_replace("'","''",$_POST['postContent']);

if ($dbConn->query("INSERT INTO `techhelp` (title,body,poster) VALUES ('$title','$body','".$acp_uid."')")) {
	$msg = "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>The post has been added and is now visible on the home page.</div>";
} else {
	$msg = "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Failed to enter the post into the database.</div>";
}

header("Location: ../switchboard/news.php?msg=".$msg);
?>