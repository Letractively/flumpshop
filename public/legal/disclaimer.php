<?php
define("PAGE_TYPE","disclaimer");
require_once "../preload.php";
$page_title = $config->getNode("messages","disclaimerHeader");

require_once dirname(__FILE__)."/../header.php";
ob_start(); //Template Buffer

echo "<h2 id='page_title'>".$config->getNode("messages","disclaimerHeader")."</h2>";
echo placeholders($config->getNode("messages","disclaimer"));

templateContent();

require_once dirname(__FILE__)."/../footer.php";
