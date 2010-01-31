<?php
require_once dirname(__FILE__)."/preload.php";
if (isset($_GET['id'])) {
	$post = new Techhelp($_GET['id']);
	$page_title = $post->getTitle();
} else {
	$page_title = "News";
}
require_once dirname(__FILE__)."/header.php";
echo "<h1 class='content'>".$config->getNode("messages","technicalHeader")."</h1>";
echo $post->view();
require_once dirname(__FILE__)."/footer.php";
?>