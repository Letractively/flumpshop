<?php require_once dirname(__FILE__)."/../preload.php"; $page_title = "Unavailable"; require_once dirname(__FILE__)."/../header.php";?>

<?php echo $config->getNode('messages','maintenance'); ?>

<?php require_once dirname(__FILE__)."/../footer.php";?>