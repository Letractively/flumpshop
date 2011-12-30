<?php
$USR_REQUIREMENT = "can_delete_products";

require_once dirname(__FILE__)."/../header.php";

$id = intval($_GET['id']);

$dbConn->query("UPDATE `products` SET active=0 WHERE id='$id' LIMIT 1");
$dbConn->query('DELETE FROM `stats` WHERE `key`="item'.$id.'Hits" LIMIT 1');

?><div class="ui-state-highlight"><span class="ui-icon ui-icon-circle-check"></span>Product Disabled. It will no longer appear in any public category lists.</div>
<p>However, it is still stored in the database, and an administrator can recover it if necessary.</p>