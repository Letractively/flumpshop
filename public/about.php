<?php
define("PAGE_TYPE","about");
require_once dirname(__FILE__)."/preload.php";
$page_title = $config->getNode('messages','aboutPageHeader');
require_once dirname(__FILE__)."/header.php";

//Buffer for templating
ob_start();

echo "<h2 id='page_title'>".$config->getNode('messages','aboutPageHeader')."</h2>";
echo "<p>".$config->getNode("messages","aboutPage")."</p>";

templateContent();

require_once dirname(__FILE__)."/footer.php";
?>