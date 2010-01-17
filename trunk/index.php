<?php require_once dirname(__FILE__)."/preload.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='keywords' content='<?php echo $config->getNode('messages','keywords');?>' />
<meta name='description' content='<?php echo $config->getNode('messages','tagline');?>' />
<title><?php echo $config->getNode('messages','name');?> | Welcome</title>
<link rel='stylesheet' href='<?php echo $config->getNode('paths','root');?>/style/style.css' type='text/css' />
<link rel='stylesheet' href='<?php echo $config->getNode('paths','root');?>/style/style_carousel.css' type='text/css' />
<script src='<?php echo $config->getNode('paths','root');?>/js/jquery.js' type='text/javascript'></script>
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jcarousel.js"></script> 
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jqueryui.js"></script> 
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jeditable.js"></script> 
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jquery.validate.min.js"></script> 
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jquery.validate.password.js"></script>
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jquery.form.js"></script> 
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/defaults.php"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo $config->getNode('paths','root');?>/style/jcarousel.css" /> 
<link rel="stylesheet" type="text/css" href="<?php echo $config->getNode('paths','root');?>/style/skins/rjc/skin.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $config->getNode('paths','root');?>/style/jquery.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $config->getNode('paths','root');?>/style/jquery-overrides.css" />
</head>
<body>
<div id='header'>
<div id="title">
<div onclick='window.location = "<?php echo $config->getNode('paths','root');?>";' style="cursor: pointer"><h1><?php echo strtolower($config->getNode('messages','name')); ?></h1>
<h1 class='drop_shadow'><?php echo strtolower($config->getNode('messages','name')); ?></h1>
<h1 class='slogan'><?php echo $config->getNode('messages','tagline');?></h1></div>
</div>
<div id='search_container'><form action='<?php echo $config->getNode('paths','root');?>/search.php'><input class='search_box' value='Search...' onfocus="if(this.value=='Search...'){this.value=''}" onblur="if(this.value==''){this.value='Search...'}" type='text' name='q' id='q' /></form></div>
<div id='tab'>
<?php //Basket
if ($config->getNode("site","shopEnabled")) {
	if (stristr($_SERVER['REQUEST_URI'],"basket.php")) {?><a class='tab active' href='<?php echo $config->getNode('paths','root');?>/basket.php'>cart</a><?php } else {?><a class='tab' href='<?php echo $config->getNode('paths','root');?>/basket.php'>cart</a><?php }
}

if (!isset($_SESSION['login']['active']) or $_SESSION['login']['active'] == false) echo "<a class='tab' onclick='loginForm();'>login</a>"; else {
	//Login/Account
	if (stristr($_SERVER['REQUEST_URI'],"/account")) {echo "<a class='tab active' href='".$config->getNode("paths","root")."/account/'>account</a>";} else {echo "<a class='tab' href='".$config->getNode("paths","root")."/account/'>account</a>";}
}

//Contact
if (stristr($_SERVER['REQUEST_URI'],"contact.php")) {?><a class='tab active' href='<?php echo $config->getNode('paths','root');?>/contact.php'>contact</a><?php } else {?><a class='tab' href='<?php echo $config->getNode('paths','root');?>/contact.php'>contact</a><?php }

//About
if (stristr($_SERVER['REQUEST_URI'],"about.php")) {?><a class='tab active' href='<?php echo $config->getNode('paths','root');?>/about.php'>about</a><?php } else {?><a class='tab' href='<?php echo $config->getNode('paths','root');?>/about.php'>about</a><?php }

/*ADMIN*/ if (isset($_SESSION['adminAuth']) and $_SESSION['adminAuth'] == true) echo "<a class='tab' href='".$config->getNode('paths','root')."/admin'>admin</a>";?>
</div>
</div>
<!--End Header-->
<div style="position: fixed; top: 99px; text-align: center; margin: 0 auto 0 auto; "><a href='<?php echo $config->getNode('paths','root');?>/reportbug.php' class="ui-state-highlight">Send Feedback</a></div>
<!--Send Feedback - Remove on Final Release-->
<div id='content_container_background'>
<div id="content_container">
  <div id="leftside">
    <?php
	$categories = $dbConn->query("SELECT id FROM `category` WHERE parent='0'");
	while ($category = $dbConn->fetch($categories)) {
		$cat = new Category($category['id']);
		echo "<a class='navigation' id='cat".$category['id']."' onclick='loadCat(\"cat".$category['id']."\",\"".$cat->getAjaxURL()."\");'>".ucwords(strtolower($cat->getName()))."</a><div class='subcat ui-corner-right'><center><img src='".$config->getNode('paths','root')."/images/loading.gif' alt='Loading Image' /><br />Loading Content...</center></div>";
	}
	?>
  </div>
  <div id='rightside'>
  <!-- Product Carousel -->
  <?php include $config->getNode('paths','path')."/includes/carousel.inc.php";?>
  </div>
  <div id="mainContent">
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
	  $result = $dbConn->query("SELECT id FROM `products` WHERE active=1 ORDER BY id DESC LIMIT 1");
	  if ($dbConn->rows($result) == 0) {
		  echo "This feature isn't available right now. Please try again later.";
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
			  echo "<li><a href='{$news->getURL()}'>".$news->getTitle()."</a></li>";
		  }
		  echo "</ul>";
	  }
	  ?>
      </div>
	</div>
<script type="text/javascript">
function loadCat(obj,url) {
	$("#leftside a:not(#"+obj+")").removeClass('activeNavigation');
	$("#"+obj).toggleClass('activeNavigation');
	$("#"+obj+" + div").load(url);
	$("#leftside div:not(#"+obj+" + div):visible").hide("fold",{},"50");
	$("#"+obj+" + div").toggle("fold");
}
</script>
<?php require_once dirname(__FILE__)."/footer.php"; ?>