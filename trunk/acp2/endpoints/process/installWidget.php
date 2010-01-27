<?php
require_once dirname(__FILE__)."/../../../preload.php";
$widget = unserialize(base64_decode(file_get_contents($_FILES['widget']['tmp_name'])));

//Import Configuration Variables
?>