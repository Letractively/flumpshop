<?php
$page_title = "Legalese | Terms and Conditions";
require_once dirname(__FILE__)."/../header.php";
echo "<h1 class='content'>Terms and Conditions</h1>";
echo placeholders($config->getNode("messages","termsConditions"));
require_once dirname(__FILE__)."/../footer.php";
?>