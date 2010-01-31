<?php
$page_title = "About Us";
require_once dirname(__FILE__)."/header.php";
echo "<h1 class='content'>About Us</h1>";
echo $config->getNode("messages","aboutPage");
require_once dirname(__FILE__)."/footer.php";
?>