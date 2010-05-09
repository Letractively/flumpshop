<?php
define("PAGE_TYPE","about");
require_once dirname(__FILE__)."/preload.php";
$page_title = $config->getNode('messages','aboutPageHeader');
require_once dirname(__FILE__)."/header.php";
echo "<div id='page_text'>";
echo "<h3 id='page_title'>".$config->getNode('messages','aboutPageHeader')."</h3>";
echo "<p>".$config->getNode("messages","aboutPage")."</p>";
echo "</div>"; //End Page Text
require_once dirname(__FILE__)."/footer.php";
?>