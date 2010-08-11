<?php
$_SUBPAGE = false;
define('PAGE_TYPE','index');
require_once 'header.php';
loadClass('Techhelp');

// Store content data
// Title
$page_title = $config->getNode('messages','titlesIndexPrefix').$config->getNode('messages','name');
// Featured Items
if ($config->getNode("homePage", "featuredItems")) { //Check Enabled
	//Find Featured Items in DB
	$result = $dbConn->query("SELECT value FROM `stats` WHERE `key` LIKE 'featuredItem%'");
	if ($dbConn->rows($result) == 0) {
		//Featured items has yet to be set up
		$featured_item_1 = $config->getNode("messages","featuredItemsPlaceholder");
		$featured_item_2 = "";
	} else {
		//Load first item
		$row = $dbConn->fetch($result);
		$item = new Item($row['value']);
		unset($row);
		$featured_item_1 = $item->getDetails("INDEX");
		unset($item);
		//Load second item
		$row = $dbConn->fetch($result);
		unset($result);
		$item = new Item($row['value']);
		unset($row);
		$featured_item_2 = $item->getDetails("INDEX");
		unset($item);
	}
} else {
	$featured_item_1 = '';
	$featured_item_2 = '';
} //End Featured Items

// Popular Items
if ($config->getNode("homePage", "popularItems")) { //Check Enabled
	//Find most popular items
	$popular = $stats->getHighestStat("item%Hits",2);
	if (empty($popular)) {
		//No statistics have been gathered yet
		$popular_item_1 = $config->getNode("messages", "popularItemsPlaceholder");
		$popular_item_2 = "";
	} else {
		//First Item
		$popular1 = intval(preg_replace("/item([0-9]*)Hits/","$1",$popular[0]));
		$item = new Item($popular1);
		unset($popular1);
		$popular_item_1 = $item->getDetails("INDEX");
		unset($item);
		//Second Item
		$popular2 = intval(preg_replace("/item([0-9]*)Hits/","$1",$popular[1]));
		unset($popular);
		$item = new Item($popular2);
		unset($popular2);
		$popular_item_2 = $item->getDetails("INDEX");
		unset($item);
	}
} else {
	$popular_item_1 = '';
	$popular_item_2 = '';
} //End Popular Items

// Latest News
if ($config->getNode('homePage', 'latestNews')) { //Check Enabled
	//Find news
	$result = $dbConn->query('SELECT * FROM `news` ORDER BY timestamp DESC LIMIT 1');
	if ($dbConn->rows($result) == 0) {
		//Placeholder
		$latest_news = $config->getNode('messages', 'noNewsPlaceholder');
		unset($result);
	} else {
		$news = $dbConn->fetch($result);
		unset($result);
		$latest_news = nl2br(nl2br($news['body']));
		unset($news);
	}
} else {
	$latest_news = '';
} //End Latest News

// Quick Tips
if ($config->getNode('homePage', 'techTips')) { //Check Enabled
	//Find Quick Tips
	$result = $dbConn->query('SELECT id FROM `techhelp` ORDER BY timestamp DESC LIMIT 4');
	if ($dbConn->rows($result) == 0) {
		//Placeholder
		$quick_tips = $config->getNode('messages', 'noNewsPlaceholder');
		unset($result);
	} else {
		$quick_tips = '';
		while ($techHelp = $dbConn->fetch($result)) {
			$news = new Techhelp($techHelp['id']);
			unset($techHelp);
			$quick_tips .= '<li><a href="'.$news->getURL().'">'.$news->getTitle().'</a></li>';
			unset($news);
		}
		unset($result);
	}
} else {
	$quick_tips = '';
} //End Tech Tips

//Actual include
require $config->getNode('paths','offlineDir').'/themes/core/'.$config->getNode('site','theme').'/index.content.tpl.php';

require_once 'footer.php';
?>