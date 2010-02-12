<?php $_SUBPAGE = false; require_once dirname(__FILE__)."/header.php";?>
  <h1 class='content'>Welcome to <?php echo $config->getNode('messages','name');?></h1>
  <noscript><div class="ui-state-error"><span class="ui-icon ui-icon-script"></span>Sorry, you need JavaScript enabled in your web browser for this site to work properly. Please enable JavaScript and reload the page.</div></noscript><?php
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
  echo $config->getNode("messages","homePage");
  if ($_PRINTDATA) echo '<div class="ui-state-highlight"><span class="ui-icon ui-icon-info"></span>Hello, my wonderful developers have put me into debug mode for a while to help with the integration of a new feature. Go ahead and ignore all the random messages that I spew out on every page while they make me even better. Hey! Guys! Don\'t do that, it tickles!</div>';
  if (!$config->getNode("server","commitPayments")) {
	  echo '<div class="ui-state-error"><span class="ui-icon ui-icon-info"></span>Hi, this site is currently in development mode. All products and services listed in this site will not genuninely be provided or are available for sale. Any attempt at purchasing will fail as the transaction is passed to a sandbox version of our payment processors.</div>';
  }
  ?><div class="ui-widget">
      <div class="ui-widget-header ui-corner-top"><?php echo $config->getNode("messages","featuredItemHeader");?></div>
      <div class="ui-widget-content" style='min-height: 150px;'>
      <?php
	  $result = $dbConn->query("SELECT value FROM `stats` WHERE `key` LIKE 'featuredItem%'");
	  if ($dbConn->rows($result) == 0) {
		  echo "Hey, It's the Flumpnet robot and this is another one of my spectacular placeholders! You can set an item to appear here by selecting it in the Admin CP, under Edit Object->Featured Item";
	  } else {
		  echo '<table width="620" style="min-height: 150px;"><tr><td style="width: 320px;">';
		  //Item 1
		  $row = $dbConn->fetch($result);
		  $item = new Item($row['value']);
		  echo $item->getDetails("INDEX");
		  echo '</td><td style="width: 50%; vertical-align: top;"><span style="width: 320px;">';
		  //Item 2 (Image Only)
		  $row = $dbConn->fetch($result);
		  $item = new Item($row['value']);
		  echo "<a href='".$item->getURL()."'><img src='".$config->getNode("paths","root")."/item/imageProvider.php?id=".$item->getID()."&image=0&size=thumb' style='width: 384px;' alt='".$item->getName()."' /></a>";
		  echo "</span></td></tr></table>";
	  }
	  ?>
    </div>
  </div>
  <div class="ui-widget">
      <div class="ui-widget-header"><?php echo $config->getNode("messages","popularItemHeader");?></div>
      <div class="ui-widget-content" style='min-height: 150px;'><table width="100%" style="min-height: 150px;"`><tr><?php
      $popular = $stats->getHighestStat("item%Hits",2);
	  if (!is_array($popular)) {
		  echo "This feature isn't available right now. Please try again later.";
	  } else {
		  echo "<td style='width: 50%;'>";
		  //First Item
		  $popular1 = intval(preg_replace("/item([0-9]*)Hits/","$1",$popular[0]));
		  $item = new Item($popular1);
		  echo $item->getDetails("INDEX");
		  echo "</td><td style='width: 50%;'>";
		  //Second Item
		  $popular2 = intval(preg_replace("/item([0-9]*)Hits/","$1",$popular[1]));
		  $item = new Item($popular2);
		  echo $item->getDetails("INDEX");
		  echo "</td>";
	  }
	  echo "</tr></table>";
	  ?></div>
	</div>
    <div class="ui-widget">
      <div class="ui-widget-header"><?php echo $config->getNode("messages","latestNewsHeader");?></div>
      <div class="ui-widget-content">
      <?php
	  $result = $dbConn->query("SELECT * FROM `news` ORDER BY timestamp DESC LIMIT 1");
	  if ($dbConn->rows($result) == 0) {
		  echo "<h2>Welcome</h2>Welcome to your new website. I've put this placeholder text in the new section for now, but it'll automatically disappear as soon as you make your first news post. You can do this by visiting my <a href='admin'>Administrator Control Panel</a>, and selecting 'Add News Post'.<br /><br />Posted: 20/12/2009";
	  } else {
		  $news = $dbConn->fetch($result);
		  echo "<h2>".$news['title']."</h2>";
		  echo nl2br(nl2br($news['body']));
	  }
	  ?>
      </div>
	</div>
    <div class="ui-widget">
      <div class="ui-widget-header"><?php echo $config->getNode("messages","technicalHeader");?></div>
      <div class="ui-widget-content ui-corner-bottom">
      <?php
	  $result = $dbConn->query("SELECT id FROM `techHelp` ORDER BY timestamp DESC LIMIT 4");
	  if ($dbConn->rows($result) == 0) {
		  echo "<h2>Technical Help</h2>Hi, this is another of my placeholder messages. Here you can place technical help, or if you change the Technical Help title in the Configuration, you can use it for whatever you want! Once again, this post will automatically disappear once you create your first post. Do this by visiting my <a href='admin'>Administrator Control Panel</a>, and selecting 'Add Tech Help Post'.<br /><br />Posted: 20/12/2009";
	  } else {
		  echo "<ul>";
		  while ($techHelp = $dbConn->fetch($result)) {
			  $news = new Techhelp($techHelp['id']);
			  echo "<li><a href='".$news->getURL()."'>".$news->getTitle()."</a></li>";
		  }
		  echo "</ul>";
	  }
	  ?>
      </div>
	</div>
<h2><a href="http://twitter.com/rjccom">Twitter Updates</a></h2>
<script type="text/javascript"> function relative_time(time_value) { var values = time_value.split(" "); time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3]; var parsed_date = Date.parse(time_value); var relative_to = (arguments.length > 1) ? arguments[1] : new Date(); var delta = parseInt((relative_to.getTime() - parsed_date) / 1000); delta = delta + (relative_to.getTimezoneOffset() * 60);
if (delta < 60) { return 'less than a minute ago'; } else if(delta < 120) { return 'about a minute ago'; } else if(delta < (45*60)) { return (parseInt(delta / 60)).toString() + ' minutes ago'; } else if(delta < (90*60)) { return 'about an hour ago'; } else if(delta < (24*60*60)) { return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago'; } else if(delta < (48*60*60)) { return '1 day ago'; } else { return (parseInt(delta / 86400)).toString() + ' days ago'; } }
function twitterCallback(obj) { var id = obj[0].user.id; document.getElementById('my_twitter_status').innerHTML = obj[0].text; document.getElementById('my_twitter_status_time').innerHTML = relative_time(obj[0].created_at); } </script> <span id="my_twitter_status"></span>
<span id="my_twitter_status_time" style="display: block;"></span>
<script type="text/javascript" src="http://www.twitter.com/statuses/user_timeline/rjccom.json?callback=twitterCallback&count=1"></script> 
</td><!-- Close MainContent -->
<td id='rightside'>
<!-- Product Carousel -->
<?php if ($config->getNode("widget_carousel", "onIndex") and $config->getNode("widget_carousel", "indexPosition") == "right") include $config->getNode('paths','path')."/includes/carousel.inc.php";?>
<script type="text/javascript">
function loadCat(obj) {
	$("#leftside_nav td:not(#cat"+obj+")").removeClass('activeNavigation');
	$("#cat"+obj).toggleClass('activeNavigation');
	$("#leftside table:not(#subcat"+obj+", #leftside_nav):visible").hide("fold",{},"50");
	$("#subcat"+obj).css('left',(($('#leftside').position().left)+160)+"px");
	$("#subcat"+obj).toggle("fold");
}

//Move subcat on window resize
$(window).bind("resize", function(e) {
								  $('.subcat:visible').css('left',(($('#leftside').position().left)+160)+"px");
								  });
//$('#leftside_nav').css('top',($('#mainContent').position().top)+"px");
</script>
<!-- Close rightside in footer -->
<?php require_once dirname(__FILE__)."/footer.php"; ?>