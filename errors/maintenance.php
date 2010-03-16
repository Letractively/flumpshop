<?php $page_title = "Unavailable";
define("PAGE_TYPE","site_disabled");
$maintPage = true;
require_once dirname(__FILE__)."/../header.php";
echo "<div id='page_text'>";
echo $config->getNode('messages','maintenance');
echo "</div>";
require_once dirname(__FILE__)."/../footer.php";?>