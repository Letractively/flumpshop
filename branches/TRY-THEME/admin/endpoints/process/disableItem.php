<?php
require_once dirname(__FILE__)."/../../../preload.php";
if (!isset($_SESSION['adminAuth']) || !$_SESSION['adminAuth']) die($config->getNode('messages','adminDenied'));
// Needs more comments Lloyd - Jake
$id = intval($_GET['id']);

$dbConn->query("UPDATE `products` SET active=0 WHERE id='$id' LIMIT 1");
?><div class="ui-state-highlight"><span class="ui-icon ui-icon-circle-check"></span>Product Disabled. It will no longer appear in any public category lists.</div>