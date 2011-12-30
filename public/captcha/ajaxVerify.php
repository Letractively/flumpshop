<?php
if (!isset($_SESSION)) session_start();
include dirname(__FILE__)."/securimage.php";
include dirname(__FILE__)."/../includes/json_encode.inc.php";
$img = new Securimage();
echo json_encode($img->check($_GET['captcha']));
?>