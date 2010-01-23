<?php $_SUBPAGE = false; require_once dirname(__FILE__)."/header.php";?>
  <h1 class='content'>Welcome to <?php echo $config->getNode('messages','name');?></h1>
  <?php
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
  ?>
  <div class="ui-widget">
      <div class="ui-widget-header ui-corner-top"><?php echo $config->getNode("messages","featuredItemHeader");?></div>
      <div class="ui-widget-content" style='height: 150px;'>
      <?php
	  $result = $dbConn->query("SELECT value FROM `stats` WHERE `key`='featuredItem' LIMIT 1");
	  if ($dbConn->rows($result) == 0) {
		  echo "Hey, It's the Flumpnet robot and this is another one of my spectacular placeholders! You can set an item to appear here by selecting it in the Admin CP, under Edit Object->Featured Item";
	  } else {
		  $row = $dbConn->fetch($result);
		  $item = new Item($row['id']);
		  echo $item->getDetails("INDEX");
	  }
	  ?>
    </div>
  </div>
  <div class="ui-widget">
      <div class="ui-widget-header"><?php echo $config->getNode("messages","popularItemHeader");?></div>
      <div class="ui-widget-content"><?php
      $popular = $stats->getHighestStat("item%Hits");
	  if (!$popular) {
		  echo "This feature isn't available right now. Please try again later.";
	  } else {
		  $popular = intval(preg_replace("/item([0-9]*)Hits/","$1",$popular));
		  $item = new Item($popular);
		  echo $item->getDetails("INDEX");
	  }
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
		  echo "<br /><br />Posted: ".date("d/m/y",strtotime($news['timestamp']));
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
</td><!-- Close MainContent -->
<td id='rightside'>
<!-- Product Carousel -->
<?php if ($config->getNode("widget_carousel", "onIndex") and $config->getNode("widget_carousel", "indexPosition") == "right") include $config->getNode('paths','path')."/includes/carousel.inc.php";?>
<script type="text/javascript">
function loadCat(obj,url) {
	$("#leftside_nav a:not(#"+obj+")").removeClass('activeNavigation');
	$("#"+obj).toggleClass('activeNavigation');
	$("#"+obj+" + div").load(url);
	$("#leftside_nav div:not(#"+obj+" + div):visible").hide("fold",{},"50");
	$("#"+obj+" + div").css('left',(($('#leftside').position().left)+160)+"px");
	$("#"+obj+" + div").toggle("fold");
}

//Move subcat on window resize
$(window).bind("resize", function(e) {
								  $('.subcat:visible').css('left',(($('#leftside').position().left)+160)+"px");
								  });
$('#leftside_nav').css('top',($('#mainContent').position().top)+"px");
</script>
<!-- Close rightside in footer -->
<?php require_once dirname(__FILE__)."/footer.php"; ?>