<?php
require_once dirname(__FILE__)."/../../../preload.php";
if ($dbConn->rows($dbConn->query("SELECT id FROM `stats` WHERE `key`='featuredItem1' LIMIT 1")) == 0) {
	$dbConn->query("INSERT INTO `stats` (`key`,value) VALUES ('featuredItem1',".intval($_GET['featuredItem1']).")");
	$dbConn->query("INSERT INTO `stats` (`key`,value) VALUES ('featuredItem2',".intval($_GET['featuredItem2']).")");
} else {
	$dbConn->query("UPDATE `stats` SET value=".intval($_GET['featuredItem1'])." WHERE `key`='featuredItem1' LIMIT 1");
	$dbConn->query("UPDATE `stats` SET value=".intval($_GET['featuredItem2'])." WHERE `key`='featuredItem2' LIMIT 1");
}

if ($dbConn->rows($dbConn->query("SELECT id FROM `stats` WHERE `key`='popularItem' LIMIT 1")) == 0) {
	$dbConn->query("INSERT INTO `stats` (`key`,value) VALUES ('popularItem',".intval($_GET['popularItem']).")");
} else {
	$dbConn->query("UPDATE `stats` SET value=".intval($_GET['popularItem'])." WHERE `key`='popularItem' LIMIT 1");
}
?><div class="ui-state-highlight"><span class="ui-icon ui-icon-circle-check"></span>Featured Item Saved</div>