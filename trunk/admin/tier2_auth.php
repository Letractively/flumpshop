<?php
require_once "../preload.php";
if (!acpusr_validate()) {echo $config->getNode("messages","adminDenied"); exit;}

if (md5($_POST['passkey']) == $config->getNode('site','password')) {
	$user = explode("~",base64_decode($_SESSION['acpusr']));
	$user = $user[0];
	$result = $dbConn->query("SELECT id FROM `acp_login` WHERE uname='".htmlentities($user,ENT_QUOTES)."' LIMIT 1");
	$row = $dbConn->fetch($result);
	$_SESSION['adminAuth'] = $row['id'];
	$dbConn->query("UPDATE `acp_login` SET last_tier2_login='".$dbConn->time()."' WHERE id=".$row['id']." LIMIT 1");
}

header("Location: ".$_POST['return']);
?>