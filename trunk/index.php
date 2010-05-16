<?php
$_SUBPAGE = false;
define("PAGE_TYPE","index");
require_once "header.php";

if (file_exists($config->getNode("paths","offlineDir")."/themes/core/".$config->getNode("site","theme")."/index.content.tpl.php")) {
	//New Template Mechanism
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
		$featured_item_1 = "";
		$featured_item_2 = "";
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
		$popular_item_1 = "";
		$popular_item_2 = "";
	} //End Popular Items
	
	// Latest News
	if ($config->getNode("homePage", "latestNews")) { //Check Enabled
		//Find news
		$result = $dbConn->query("SELECT * FROM `news` ORDER BY timestamp DESC LIMIT 1");
		if ($dbConn->rows($result) == 0) {
			//Placeholder
			$latest_news = $config->getNode("messages", "noNewsPlaceholder");
			unset($result);
		} else {
			$news = $dbConn->fetch($result);
			unset($result);
			$latest_news = nl2br(nl2br($news['body']));
			unset($news);
		}
	} else {
		$latest_news = "";
	} //End Latest News
	
	// Quick Tips
	if ($config->getNode("homePage", "techTips")) { //Check Enabled
		//Find Quick Tips
		$result = $dbConn->query("SELECT id FROM `techhelp` ORDER BY timestamp DESC LIMIT 4");
		if ($dbConn->rows($result) == 0) {
			//Placeholder
			$quick_tips = $config->getNode("messages", "noNewsPlaceholder");
			unset($result);
		} else {
			$quick_tips = "";
			while ($techHelp = $dbConn->fetch($result)) {
				$news = new Techhelp($techHelp['id']);
				unset($techHelp);
				$quick_tips .= "<li><a href='".$news->getURL()."'>".$news->getTitle()."</a></li>";
				unset($news);
			}
			unset($result);
		}
	} else {
		$quick_tips = "";
	} //End Tech Tips
	
	//Actual include
	require $config->getNode("paths","offlineDir")."/themes/core/".$config->getNode("site","theme")."/index.content.tpl.php";
	
} elseif (file_exists($config->getNode("paths","offlineDir")."/themes/core/".$config->getNode("site","theme")."/content.tpl.php")) {
	echo "Sorry, Flumpshop requires a specific tpl file for the home page at the current development stage.";
} else {
    ?><div id="page_text">
        <h3 id="page_title"><?php echo $config->getNode('messages','titlesIndexPrefix').$config->getNode('messages','name');?></h3><?php
		//Check if home page text is enabled
		if ($config->getNode("homePage", "pageText")) {
        	echo "<p>".$config->getNode("messages","homePage")."</p>";
		} //End Page Text
		//GET Notices
		if (isset($_GET['loginSuccess'])) {
		  echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Login Successful!</div>";
		}
		if (isset($_GET['unknownUname'])) {
		  echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Unknown Username</div>";
		}
		if (isset($_GET['invalidPass'])) {
		  echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Incorrect Password</div>";
		}
		if (isset($_GET['loggedOut'])) {
		  echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Logged Out</div>";
		}
		//End GET Notices
	?></div><?php
	//Check if Featured Items is enabled
	if ($config->getNode("homePage", "featuredItems")) {
		?><div id="featured_items">
			<h4 id="featured_items_header"><?php echo $config->getNode("messages","featuredItemHeader");?></h4>
			<div id="featured_items_container">
				<div class="featured_item" id="featured_item_1"><?php
					//Find Featured Items in DB
					$result = $dbConn->query("SELECT value FROM `stats` WHERE `key` LIKE 'featuredItem%'");
					if ($dbConn->rows($result) == 0) {
						//Featured items has yet to be set up
						echo $config->getNode("messages","featuredItemsPlaceholder");
					} else {
						//Load first item
						$row = $dbConn->fetch($result);
						$item = new Item($row['value']);
						echo $item->getDetails("INDEX");
					?></div><div class="featured_item" id="featured_item_2"><?php
						//Load second item
						$row = $dbConn->fetch($result);
						$item = new Item($row['value']);
						echo $item->getDetails("INDEX");
					}
				?></div>
			</div>
		</div><div class='clear'>&nbsp;</div><?php
	} //End Featured Items
	//Check if Featured Items is enabled
	if ($config->getNode("homePage", "popularItems")) {
		?><div id="popular_items">
			<h4 id="popular_items_header"><?php echo $config->getNode("messages","popularItemHeader");?></h4>
			<div id="popular_items_container">
				<div class="popular_item" id="popular_item_1"><?php
				//Find most popular items
				$popular = $stats->getHighestStat("item%Hits",2);
				if (empty($popular)) {
					//No statistics have been gathered yet
					echo $config->getNode("messages", "popularItemsPlaceholder");
				} else {
					//First Item
					$popular1 = intval(preg_replace("/item([0-9]*)Hits/","$1",$popular[0]));
					$item = new Item($popular1);
					echo $item->getDetails("INDEX");
					?></div>
					<div class="popular_item" id="popular_item_2"><?php
					//Second Item
					$popular2 = intval(preg_replace("/item([0-9]*)Hits/","$1",$popular[1]));
					$item = new Item($popular2);
					echo $item->getDetails("INDEX");
				}
				?></div>
			</div>
		</div><div class='clear'>&nbsp;</div><?php
	} //End Popular Items
	//Check if Latest News is enabled
	if ($config->getNode("homePage", "latestNews")) {
		?><div id="latest_news">
			<h4><?php echo $config->getNode("messages","latestNewsHeader");?></h4>
			<p><?php
			//Find news
			$result = $dbConn->query("SELECT * FROM `news` ORDER BY timestamp DESC LIMIT 1");
			if ($dbConn->rows($result) == 0) {
				//Placeholder
				echo $config->getNode("messages", "noNewsPlaceholder");
			} else {
				$news = $dbConn->fetch($result);
				echo nl2br(nl2br($news['body']));
			}
			?></p>
		</div><div class='clear'>&nbsp;</div><?php
	} //End Latest News
	//Check if Tech Tips is enabled
	if ($config->getNode("homePage", "techTips")) {
		?><div id="tech_tips">
			<h4><?php echo $config->getNode("messages","technicalHeader");?></h4>
			<ul><?php
			$result = $dbConn->query("SELECT id FROM `techhelp` ORDER BY timestamp DESC LIMIT 4");
			if ($dbConn->rows($result) == 0) {
				//Placeholder
				echo $config->getNode("messages", "noNewsPlaceholder");
			} else {
				while ($techHelp = $dbConn->fetch($result)) {
					$news = new Techhelp($techHelp['id']);
					echo "<li><a href='".$news->getURL()."'>".$news->getTitle()."</a></li>";
				}
			}
		  ?></ul>
		</div><div class='clear'>&nbsp;</div><?php
	} //End Tech Tips
	
} //End Legacy Template
require_once "footer.php";
?>