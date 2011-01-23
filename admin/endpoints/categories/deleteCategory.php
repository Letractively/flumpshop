<?php
$USR_REQUIREMENT = "can_delete_categories";

require_once dirname(__FILE__)."/../header.php";

$id = intval($_GET['id']);

$dbConn->query("UPDATE `category` SET enabled=0 WHERE id='$id' LIMIT 1");

$string = '<div class="ui-state-highlight"><span class="ui-icon ui-icon-circle-check"></span>Category Disabled. It will no longer appear in any public category lists.</div><p>However, it is still stored in the database, and an administrator can recover it if necessary.</p>';

header("Location: ../switchboard/categories.php?msg=$string");
?>