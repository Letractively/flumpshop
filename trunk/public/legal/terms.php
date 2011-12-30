<?php
define("PAGE_TYPE","termsConditions");
require_once "../preload.php";
$page_title = $config->getNode("messages","termsConditionsHeader");

require_once dirname(__FILE__)."/../header.php";
ob_start(); //Template Buffer

echo "<h2 id='page_title'>".$config->getNode("messages","termsConditionsHeader")."</h2>";
echo placeholders($config->getNode("messages","termsConditions"));

templateContent();

require_once dirname(__FILE__)."/../footer.php";
