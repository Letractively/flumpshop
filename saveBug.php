<?php
require_once dirname(__FILE__)."/preload.php";
loadClass('Mail');

if (!isset($_POST['bugHeader']) or !isset($_POST['body'])) die();

$dbConn->query("INSERT INTO `bugs` (header,body) VALUES ('".str_replace("'","''",$_POST['bugHeader']." From ".$_POST['email'])."','".str_replace("'","''",$_POST['body'])."')");

//Send email if SMTP configured
if ($config->getNode("smtp","host") != "") {
	$handle = new Mail();
	$handle->send($config->getNode("site","name"),$config->getNode("messages","email"),"New Feedback!","Hello,<br />You are receiving this email because someone has sent feedback to your website, ".$config->getNode("paths","root").". You can view the full message by logging in <a href='".$config->getNode("paths","root")."/admin'>here</a> then selecting Advanced->Feedback.<br /><br /><b>Subject: </b>".str_replace("'","''",$_POST['bugHeader'])."<br /><br /><b>From:</b>".str_replace("'","''",$_POST['email'])."<br /><b>Message:</b><br />".str_replace("'","''",$_POST['body'])."<br /><br />~Flumpnet Robot");
	
	$handle->send($config->getNode("site","name"),$_POST['email'],"","Hello,<br /><br />Thank you for getting in touch with ".$config->getNode("site","name")." with your query.<br /><br />We have a team of knowledgable people who will do their best to help, and aim to respond to all messages within 24 hours. If you need to supply further information, feel free to reply to this email.<br /><br />".$config->getNode('site','name'));
}

$url = $_SERVER['HTTP_REFERER'];

$return = "posted";
//Remove Previous Reports
$url = str_replace(array("posted&","&posted","?posted"),"",$url);

if (strstr($url,"?")) {
	$url .= "&$return";
} else {
	$url .= "?$return";
}

header("Location: ".$url);
?>