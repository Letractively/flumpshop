<?php $page_title = "Unavailable";
define("PAGE_TYPE","site_disabled");
$maintPage = true;
require_once dirname(__FILE__)."/../header.php";

echo $config->getNode('messages','maintenance');

require_once dirname(__FILE__)."/../footer.php";?>