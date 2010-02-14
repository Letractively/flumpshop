<?php
require_once dirname(__FILE__)."/../header.php";

$id = intval($_GET['cid']);

$dbConn->query("UPDATE `category` SET enabled=0 WHERE id='$id' LIMIT 1");
echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-info'></span>Category Disabled. It will no longer appear in any public category lists.</div>";
include dirname(__FILE__)."/../edit/editCategory.php";
?>