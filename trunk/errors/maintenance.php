<?php
$maintPage = true;
$page_title = $config->getNode("messages","maintenanceHeader");
require_once dirname(__FILE__)."/../preload.php";
define("PAGE_TYPE","site_disabled");
require_once dirname(__FILE__)."/../header.php";
ob_start();
echo "<div id='page_text'>";
echo $config->getNode('messages','maintenance');
echo "</div>";
templateContent();
require_once dirname(__FILE__)."/../footer.php";
