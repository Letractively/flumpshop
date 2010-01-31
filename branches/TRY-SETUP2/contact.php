<?php
$page_title = "Contact Us";
require_once dirname(__FILE__)."/header.php";
echo "<h1 class='content'>Contact Us</h1>";
echo $config->getNode("messages","contactPage");
require_once dirname(__FILE__)."/footer.php";
?>