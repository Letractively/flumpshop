<?php
define("PAGE_TYPE","privacyPolicy");
require_once "../preload.php";
$page_title = $config->getNode("messages","privacyPolicyHeader");

require_once dirname(__FILE__)."/../header.php";
ob_start(); //Template Buffer

echo "<h2 id='page_title'>".$config->getNode("messages","privacyPolicyHeader")."</h2>";
echo placeholders($config->getNode("messages","privacyPolicy"));

templateContent();

require_once dirname(__FILE__)."/../footer.php";
