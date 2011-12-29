<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/preload.php";
if (!isset($_GET['file'])) exit;
if (strstr($_GET['file'],"..")) {
	header("HTTP/1.1 401 Unauthorized");
	die("You have attempted to leave the scope of the download directory. Access Denied.");
} else {
	if (!file_exists($config->getNode("paths","offlineDir")."/files/".$_GET['file'])) {
		header("Location: errors/404.php"); exit;
	}
	if ($config->getNode("temp","fileinfo")) {
		$info = finfo_open(FILEINFO_MIME_TYPE);
		$type = finfo_file($info,$config->getNode("paths","offlineDir")."/files/".$_GET['file']);
	} elseif (function_exists("mime_content_type")) {
		$type = mime_content_type($config->getNode("paths","offlineDir")."/files/".$_GET['file']);
	} else {
		//Guess by extension
		$ext = substr($_GET['file'],strlen($_GET['file'])-4);
		switch ($ext) {
			case ".pdf":
			$type = "application/pdf";
			break;
			default:
			$type = "application/octet-stream";
		}
	}
	
	if ($type != "application/pdf") header("Content-Disposition: attachment; filename=".$_GET['file']); //Show PDFs in Browser
	header("Content-Type: ".$type);
	echo file_get_contents($config->getNode("paths","offlineDir")."/files/".$_GET['file']);
}
?>