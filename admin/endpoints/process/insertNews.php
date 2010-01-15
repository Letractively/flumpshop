<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../../preload.php";
if (!isset($_SESSION['adminAuth']) || !$_SESSION['adminAuth']) $config->getNode('messages','adminDenied');

$title = str_replace("'","''",$_POST['postTitle']);
$body = str_replace("'","''",$_POST['postContent']);

$dbConn->query("INSERT INTO `news` (title,body) VALUES ('$title','$body')");
?>
<h2 class="content">Post Created</h2>
<p>Your new post has been added and is now visible on the home page.</p>