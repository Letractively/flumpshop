<?php
require_once dirname(__FILE__)."/preload.php";
$page_title = $config->getNode('messages','contactPageHeader');
require_once dirname(__FILE__)."/header.php";

ob_start();

echo "<h3 id='page_title'>".$config->getNode('messages','contactPageHeader')."</h3>";
echo "<p>".$config->getNode("messages","contactPage")."</p>";

templateContent();

require_once dirname(__FILE__)."/footer.php";
?>