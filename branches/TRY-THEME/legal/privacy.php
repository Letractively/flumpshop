<?php
$page_title = "Legalese | Privacy Policy";
require_once dirname(__FILE__)."/../header.php";
echo "<h1 class='content'>Privacy Policy</h1>";
echo placeholders($config->getNode("messages","privacyPolicy"));
require_once dirname(__FILE__)."/../footer.php";
?>