<?php $page_title = "Unavailable";
$maintPage = true;
require_once dirname(__FILE__)."/../header.php";

echo $config->getNode('messages','maintenance');

require_once dirname(__FILE__)."/../footer.php";?>