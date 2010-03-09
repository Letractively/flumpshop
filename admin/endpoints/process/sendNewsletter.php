<?php
require_once "../header.php";
//Takes the preview snapshot stored in $_SESSION and emails it to everyone.

$mailer = new Mail();
$mailer->send("Lloyd Wallis", "lloyd@theflump.com", $_SESSION['newsletter_title'], $_SESSION['newsletter_cache']);
?>