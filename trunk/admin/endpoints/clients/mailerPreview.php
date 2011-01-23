<?php
ob_start();
require_once dirname(__FILE__)."/../../../preload.php";

echo '<html>';

$mail_content = $_POST['email'];

include $config->getNode('paths','offlineDir').'/themes/core/'.$config->getNode('site','theme').'/mailer.tpl.php';


echo '</html>';

//Store page output to session
$_SESSION['newsletter_cache'] = ob_get_contents();
$_SESSION['newsletter_title'] = $_POST['title'];
ob_end_flush();
