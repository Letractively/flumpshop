<?php require_once dirname(__FILE__)."/../preload.php";
if (isset($_POST['submit'])) {
	if (md5($_POST['password']) == $config->getNode('site','password')) {
		$_SESSION['adminAuth'] = true;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='keywords' content='<?php echo $config->getNode('messages','keywords');?>' />
<meta name='description' content='<?php echo $config->getNode('messages','tagline');?>' />
<title><?php echo $config->getNode('messages','name');?> | Control Panel</title>
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
<a href='<?php echo $config->getNode('paths','root');?>'><h1><?php echo strtolower($config->getNode('messages','name')); ?></h1>
<h1 class='drop_shadow'><?php echo strtolower($config->getNode('messages','name')); ?></h1>
<h1 class='slogan'><?php echo $config->getNode('messages','tagline');?></h1></a>
</div>
<div id='search_container'><form action='<?php echo $config->getNode('paths','root');?>/search.php'><input class='search_box' value='Search...' onfocus="if(this.value=='Search...'){this.value=''}" onblur="if(this.value==''){this.value='Search...'}" type='text' name='q' id='q' /></form></div></div>
<!--End Header-->
<a href='<?php echo $config->getNode('paths','root');?>/reportbug.php' class="ui-state-highlight" style='position: fixed; top: 99px; text-align: center;'>Send Feedback</a><br /><!--Send Feedback - Remove on Final Release-->
<div id='content_container_background'>
<div id="content_container">
<center><table><tr>
  <td id="leftside">
  <?php
  if (isset($_SESSION['adminAuth']) && $_SESSION['adminAuth']) {
	?>
    <a class="navigation" onclick='loadCat("addObj");' id='addObj'>Create Object</a>
    	<div class="subcat ui-corner-right" id="addObjMenu">
        	<a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/create/newItem.php');$('#addObjMenu').toggle('fold');">Item</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/create/newCategory.php');$('#addObjMenu').toggle('fold');">Category</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/create/newNews.php');$('#addObjMenu').toggle('fold');">News Post</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/create/newTechHelp.php');$('#addObjMenu').toggle('fold');">Technical Tips Post</a>
        </div>
    <a class="navigation" onclick='loadCat("editObj");' id='editObj'>Edit Object</a>
    	<div class="subcat ui-corner-right" id="editObjMenu">
        	<a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/edit/editItems.php');$('#editObjMenu').toggle('fold');">Item</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/edit/editCategory.php');$('#editObjMenu').toggle('fold');">Category</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/edit/editPageContent.php?pageid=privacyPolicy');$('#editObjMenu').toggle('fold');">Privacy Policy</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/edit/editPageContent.php?pageid=disclaimer');$('#editObjMenu').toggle('fold');">Disclaimer</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/edit/editPageContent.php?pageid=termsConditions');$('#editObjMenu').toggle('fold');">Terms and Conditions</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/edit/editPageContent.php?pageid=homePage');$('#editObjMenu').toggle('fold');">Home Page</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/edit/editPageContent.php?pageid=aboutPage');$('#editObjMenu').toggle('fold');">About Page</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/edit/editPageContent.php?pageid=contactPage');$('#editObjMenu').toggle('fold');">Contact Page</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/edit/editFeatured.php');$('#editObjMenu').toggle('fold');">Featured Item</a>
        </div>

    <a class="navigation" onclick='loadCat("orders");' id='orders'>View Orders</a>
    	<div class="subcat ui-corner-right" id="ordersMenu">
        	<a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/orders/listOrders.php?filter=active');$('#ordersMenu').toggle('fold');">Active</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/orders/listOrders.php?filter=closed');$('#ordersMenu').toggle('fold');">Closed</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/orders/queryOrder.php');$('#ordersMenu').toggle('fold');">Query</a>
        </div>
        
    <a class="navigation" onclick="loadCat('delivery');" id="delivery">Delivery Settings</a>
    	<div class="subcat ui-corner-right" id="deliveryMenu">
        	<a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/delivery/countries.php');$('#deliveryMenu').toggle('fold');">Supported Countries</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/delivery/deliveryRates.php');$('#deliveryMenu').toggle('fold');">Delivery Rates</a>
        </div>
        
    <a class="navigation" onclick='loadCat("adv");' id='adv'>Advanced</a>
    	<div class="subcat ui-corner-right" id="advMenu">
        	<a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/process/cron.php');$('#advMenu').toggle('fold');">Cron Script</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./logs');$('#advMenu').toggle('fold');">Log Viewer</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/advanced/bugs.php');$('#advMenu').toggle('fold');">Bug Reports</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);loadVarMan(loadingString);$('#advMenu').toggle('fold');">Configuration Manager</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/advanced/export.php');$('#advMenu').toggle('fold');">Export</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/advanced/import.php');$('#advMenu').toggle('fold');">Import</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/advanced/phpinfo.php');$('#advMenu').toggle('fold');">PHP Info</a>
            <a class='subcat ui-corner-right' onclick="$('#adminContent').html(loadMsg('Rebuilding Images. This may take several minutes.');$('#empty').html(null);$('#adminContent').load('./endpoints/advanced/recreateImages.php');$('#advMenu').toggle('fold');">Rebuild Images</a>
        </div>
    <a class="navigation" onclick="$('#adminContent').html(null);">Close</a>
    <?php  
  }
  ?> 
  </td>
  <td id="mainContent" style="width: 800px;">
  <script type="text/javascript">
  var loadingString = "<center><img src='<?php echo $config->getNode('paths','root')."/images/loading.gif"; ?>' /><br />Loading Content...</center>";

  function loadVarMan(loadingString) {
	  $('#adminContent').html(loadingString);
	  $('#empty').html(null);
	  $('#adminContent').load('./endpoints/advanced/varMan.php',{},
							  function() {
								  $('#varForm').ajaxForm({
														 success: function(response) {
																			 $('#adminContent').html(response);
																			 }
															})});
  }
  
  function loadCat(obj) {
	$("#leftside a:not(#"+obj+")").removeClass('activeNavigation');
	$("#"+obj).toggleClass('activeNavigation');
	$("#leftside div:not(#"+obj+" + div):visible").hide("fold");
	$("#"+obj+" + div").toggle("fold");
	}
  </script>
  	<?php
		//Check for HTTPS
		if ($config->getNode('secure','admin') && $_SERVER["HTTPS"] == "off") {
			echo "The admin section must be accessed using the HTTPS protocol. Click <a href='".$config->getNode('paths','secureRoot')."/admin'>here</a> to go to the HTTPS site.";
		} else {
			if (!isset($_SESSION['adminAuth']) || !$_SESSION['adminAuth']) {
				?>
				<h1 class="content">Authentication Required</h1>
				<p>Please enter the administrator password in order to view the full administrator panel. Please note that this is not the same as the password for the Webmaster account used in the site frontend.</p>
				<form action="./" class="ui-widget ui-widget-content ui-corner-all" method="post">
					<label for="adminPass">Password: </label><input class="ui-state-default" type="password" name="password" id="password" />
					<input type="submit" value="Login" name="submit" class="ui-state-default" />
				</form>
				<?php
			} else {
					//Update Checker
				    $version = file_get_contents("http://flumpshop.googlecode.com/svn/updater/version.txt");
				    if ($version > $config->getNode("site","version")) {
					    ?><div class="ui-state-highlight"><span class="ui-icon ui-icon-refresh"></span><strong>Update Available</strong> - Version <?php echo $version;?> is now available for download. <a href="./upgrade">Click Here to Upgrade</a></div><?php
				  }
				  ?>
				<h1 class='content'>Control Panel</h1>
				<div id="adminContent" class="ui-widget ui-corner-all" style="max-height: 500px; overflow: auto;">Use the menu on the left to load an administration tool.</div>
                <div id="empty"></div>
				<?php
			}
		}?>
        <script>
		//deliveryRates.php add form (recursive elements)
		function addCountrySelector() {
			var id = $('#counter').val();
			$.ajax({url: './endpoints/delivery/countrySelect.php?id='+id, success: function(data, textStatus) {$("#countrySelectors").append(data+"<br />");}, cache: true});
			id++;
			$('#counter').val(id);
		}
		$.validator.setDefaults({errorClass: "ui-state-error"});
		</script>
</td></tr></table></center>
</div>
<div id="dialog" class="ui-helper-hidden"></div>
<div id='footer'><?php echo $config->getNode('messages','footer');?><!--Site Designed by Jake Mitchell. Programmed by Lloyd Wallis and John Maydew.--></div>
</body>
</html>
