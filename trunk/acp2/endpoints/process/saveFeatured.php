<?php
require_once dirname(__FILE__)."/../../../preload.php";
if ($dbConn->rows($dbConn->query("SELECT id FROM `stats` WHERE `key`='featuredItem' LIMIT 1")) == 0) {
	$dbConn->query("INSERT INTO `stats` (`key`,value) VALUES ('featuredItem',".intval($_GET['featuredItem']).")");
} else {
	$dbConn->query("UPDATE `stats` SET value=".intval($_GET['featuredItem'])." WHERE `key`='featuredItem' LIMIT 1");
}
?><div class="ui-state-highlight"><span class="ui-icon ui-icon-circle-check"></span>Featured Item Saved</div>