<?php
require_once "header.php";
    ?><div id="page_text">
        <h3 id="page_title">Welcome to <?php echo $config->getNode('messages','name');?></h3>
        <p><?php echo $config->getNode("messages","homePage");?></p><?php
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
	?></div><!-- End Page Text -->
    <div id="featured_items">
        <h4 id="featured_items_header"><?php echo $config->getNode("messages","featuredItemHeader");?></h4>
        <div class="featured_item" id="featured_item_1"><?php
            //Find Featured Items in DB
            $result = $dbConn->query("SELECT value FROM `stats` WHERE `key` LIKE 'featuredItem%'");
            //Load first item
            $row = $dbConn->fetch($result);
            $item = new Item($row['value']);
            echo $item->getDetails("INDEX");
        ?></div>
        <div class="featured_item" id="featured_item_2"><?php
			//Load second item
        	$row = $dbConn->fetch($result);
			$item = new Item($row['value']);
            echo $item->getDetails("INDEX");
		?></div>
    </div><!-- End Featured Items -->
    <div id="popular_items">
        <h4 id="popular_items_header"><?php echo $config->getNode("messages","popularItemHeader");?></h4>
        <div class="popular_item" id="popular_item_1"><?php
		//Find most popular items
        $popular = $stats->getHighestStat("item%Hits",2);
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
		?></div>
    </div><!-- End Popular Items -->
    <div id="latest_news">
        <h4><?php echo $config->getNode("messages","latestNewsHeader");?></h4>
        <p><?php
		//Find news
        $result = $dbConn->query("SELECT * FROM `news` ORDER BY timestamp DESC LIMIT 1");
		if ($dbConn->rows($result) == 0) {
			//Placeholder
			echo "Nothing has been posted here.";
		} else {
			$news = $dbConn->fetch($result);
			echo nl2br(nl2br($news['body']));
		}
		?></p>
    </div><!-- End Latest News -->
    <div id="tech_tips">
        <h4><?php echo $config->getNode("messages","technicalHeader");?></h4>
        <ul><?php
	  	$result = $dbConn->query("SELECT id FROM `techHelp` ORDER BY timestamp DESC LIMIT 4");
	  	if ($dbConn->rows($result) == 0) {
			echo "Nothing has been posted here.";
	  	} else {
			while ($techHelp = $dbConn->fetch($result)) {
				$news = new Techhelp($techHelp['id']);
			  	echo "<li><a href='".$news->getURL()."'>".$news->getTitle()."</a></li>";
		  	}
	  	}
	  ?></ul>
    </div><!-- End Tech Tips -->
    <div id="footer">
    <p><?php echo $config->getNode('messages','footer');?></p>
    </div>
</div><!--End Container-->
</body>
</html>