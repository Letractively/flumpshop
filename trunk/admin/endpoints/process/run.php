<?php
//Runs PHP
$requires_tier2 = true;
require_once dirname(__FILE__)."/../../../preload.php";
acpusr_validate();
eval($_POST['query']);
echo "Code executed. The fact that you can view this message is a good sign.";
?>