<?php
require_once dirname(__FILE__)."/preload.php";
$config->setNode("messages", "contactPageHeader", "Contact Us", "Contact Us Page Header");
$page_title = $config->getNode('messages','contactPageHeader');
require_once dirname(__FILE__)."/header.php";
echo "<div id='page_text'>";
echo "<h3 id='page_title'>".$config->getNode('messages','contactPageHeader')."</h3>";
echo "<p>".$config->getNode("messages","contactPage")."</p>";
echo "</div>"; //End Page Text
require_once dirname(__FILE__)."/footer.php";
?>