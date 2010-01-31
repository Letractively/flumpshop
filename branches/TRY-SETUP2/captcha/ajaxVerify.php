<?php
include dirname(__FILE__)."/securimage.php";
$img = new Securimage();
echo json_encode($img->check($_GET['captcha']));
?>