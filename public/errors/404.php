<?php
define('PAGE_TYPE','404');
header('HTTP/1.1 404 File Not Found');
$page_title = 'Page Not Found';
require_once dirname(__FILE__).'/../header.php';

ob_start();

echo $config->getNode('messages','404');

templateContent();

require_once dirname(__FILE__)."/../footer.php";?>