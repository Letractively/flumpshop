<?php header("HTTP/1.1 404 File Not Found"); $page_title = "Invisible"; require_once dirname(__FILE__)."/../header.php";?>

<?php echo $config->getNode('messages','404'); ?>

<?php require_once dirname(__FILE__)."/../footer.php";?>