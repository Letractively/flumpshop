<?php
$page_title = "Legalese | Disclaimer";
require_once dirname(__FILE__)."/../header.php";
echo "<h1 class='content'>Disclaimer</h1>";
echo placeholders($config->getNode("messages","disclaimer"));
require_once dirname(__FILE__)."/../footer.php";
?>