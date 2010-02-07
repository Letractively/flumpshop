<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../preload.php";
//Process Login
if (isset($_POST['pass']) && md5($_POST['pass']) == $config->getNode("site","password")) {
	$_SESSION['adminAuth'] = true;
	header("Location: ./");
	die();
}
//Not Logged In
if (!isset($_SESSION['adminAuth']) or $_SESSION['adminAuth'] !== true) {
	//Login Page
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"
><head>
<style type="text/css">
body {background-color: #1e2b5b; color: #FFF; font: Arial, Helvetica, sans-serif;}
form {width: 300px; margin: 150px auto 0 auto;}
.header {font: "Trebuchet MS", Arial, Helvetica, sans-serif; font-size: 23px;}
.header2 {color: #c43131;}
.title {background-color: #0e78ee; padding: 0 5px; line-height: 1.5; font-size: 17px;}
.content {background-color: #e7e7e7; padding: 0 5px; color: #000; font-size: 12px;}
.content label {color: #1e2b5b;}
table td {text-align: right;}
input {border: 1px solid #1e2b5b; color: #1e2b5b; width: 200px;}
input.submit {width: auto; position: relative; left: 220px; border: 3px outset #1e2b5b; background: #FFF; color: #1e2b5b; font-size: 14px; font-weight: bold;}
</style>
<title>Flumpshop Login</title></head><body>
<form action="./" method="post">
<div class="header"><img src="images/logo.jpg" alt="Flumpshop Logo" />flump<span class='header2'>shop</span></div>
<div class="title">please login...</div>
<div class="content">
Please enter your username and password to continue...
<table>
<tr><td><label for='uname'>Username: </label></td><td><input type="text" name="uname" id="uname" disabled="disabled" /></td></tr>
<tr><td><label for='pass'>Password: </label></td><td><input type="password" name="pass" id="pass" /></td></tr>
</table>
<input type="submit" class="submit" value="Login" />
</div>
</form>
</body></html><?php
} else {
	//Logged In
	if (isset($_GET['frame'])) {
		if ($_GET['frame'] == "leftFrame") {
			//Left Frame
?><html>
<head>
<link href="jqueryui.css" rel="stylesheet" type="text/css" />
<link href="style-nav.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../js/jqueryui-1-8.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/defaults.php"></script>
</head>
<body>
    <center><img src="images/logo.jpg" />
    <div class="header">flump<span class='header2'>shop</span> <?php echo $config->getNode("site","version");?></div>
    Powered by Flumpnet<br />
    <!--<a href="javascript:void(0);">Expand All</a> | <a href="javascript:void(0);"> Collapse All</a><br />--><br />
    <div id="navContainer">
    <div id="navAccordion">
        <h3>Products</h3>
        <div>
            <a href='endpoints/create/newItem.php' onclick='loader("Loading Content...");' target="main">New Item</a>
            <a href='endpoints/edit/editItems.php' onclick='loader("Loading Content...");' target="main">Edit Item</a>
            <a href='endpoints/edit/editFeatured.php' onclick='loader("Loading Content...");' target="main">Featured Items</a>
        </div>
        <h3>Categories</h3>
        <div>
            <a href='endpoints/create/newCategory.php' onclick='loader("Loading Content...");' target="main">New Category</a>
            <a href='endpoints/edit/editCategory.php' onclick='loader("Loading Content...");' target="main">Edit Category</a>
        </div>
        <h3>News</h3>
        <div>
            <a href='endpoints/create/newNews.php' onclick='loader("Loading Content...");' target="main">New News Post</a>
            <a href='endpoints/create/newTechHelp.php' onclick='loader("Loading Content...");' target="main">New Technical Tips Post</a>
        </div>
        <h3>Pages</h3>
        <div>
            <a href='endpoints/edit/editPageContent.php?pageid=homePage' onclick='loader("Loading Content...");' target="main">Home Page</a>
            <a href='endpoints/edit/editPageContent.php?pageid=aboutPage' onclick='loader("Loading Content...");' target="main">About Page</a>
            <a href='endpoints/edit/editPageContent.php?pageid=contactPage' onclick='loader("Loading Content...");' target="main">Contact Page</a>
            <a href='endpoints/edit/editPageContent.php?pageid=privacyPolicy' onclick='loader("Loading Content...");' target="main">Privacy Policy</a>
            <a href='endpoints/edit/editPageContent.php?pageid=disclaimer' onclick='loader("Loading Content...");' target="main">Disclaimer</a>
            <a href='endpoints/edit/editPageContent.php?pageid=termsConditions' onclick='loader("Loading Content...");' target="main">Terms and Conditions</a>
        </div>
        <h3>Orders</h3>
        <div>
            <a href='endpoints/orders/listOrders.php?filter=active' onclick='loader("Loading Content...");' target="main">Active</a>
            <a href='endpoints/orders/listOrders.php?filter=closed' onclick='loader("Loading Content...");' target="main">Closed</a>
            <a href='endpoints/orders/queryOrder.php' onclick='loader("Loading Content...");' target="main">Query</a>
        </div>
        <h3>Deliveries</h3>
        <div>
        	<a href="endpoints/delivery/countries.php" onClick="loader('Loading Content...');" target="main">Supported Countries</a>
            <a href="endpoints/delivery/deliveryRates.php" onClick="loader('Loading Content...');" target="main">Delivery Rates</a>
        </div>
        <h3>Advanced</h3>
        <div>
            <a href="endpoints/process/cron.php" onclick='loader("Executing Cron Script...");' target="main">Cron Script</a>
            <a href="endpoints/advanced/upload.php" onclick='loader("Loading Content...");' target="main">File Upload</a>
            <a href="logs" onclick='loader("Loading Content...");' target="main">Log Viewer</a>
            <a href="endpoints/advanced/bugs.php" onclick='loader("Loading Content...");' target="main">Bugs</a>
            <a href="endpoints/advanced/query.php" onclick='loader("Loading Content...");' target="main">Execute SQL</a>
            <a href="endpoints/advanced/varMan.php" onclick='loader("Loading Content...");' target="main">Configuration Manager</a>
            <a href="endpoints/advanced/export.php" onclick='loader("Loading Content...");' target="main">Export</a>
            <a href="endpoints/advanced/import.php" onclick='loader("Loading Content...");' target="main">Import</a>
            <a href="endpoints/advanced/phpinfo.php" onclick='loader("Loading Content...");' target="main">PHP Info</a>
            <a href="endpoints/advanced/recreateImages.php" onclick='loader("Rebuilding Images. This process may take several hours.");' target="main">Rebuild Images</a>
        </div>
    </div>
    </div>
    </center>
    <script type="text/javascript">
    $(document).ready(function() {
							   $('#navAccordion').accordion({collapsible: true, active: false, autoHeight: false, icons: {'header': 'ui-icon-circle-arrow-e', 'headerSelected': 'ui-icon-circle-arrow-s'}});
							   });
	function loader(str) {
		$(parent.main.document.getElementById('dialog')).html(loadMsg(str)).dialog();
	}
    </script>
</body>
</html><?php
		} elseif ($_GET['frame'] == "header") {
			//Header Frame
			?><html>
				<head><link href="style-header.css" rel="stylesheet" type="text/css" /></head>
				<body>
				<h1>ADMINISTRATOR CONTROL PANEL</h1>
				<p class="version">Latest Version Available: <?php echo file_get_contents("http://flumpshop.googlecode.com/svn/updater/version.txt");?> <a href='upgrade' target='_top'>Upgrade Wizard</a></p>
				<div class="right">
					<h1>flump<span class="header2">shop</span> <?php echo $config->getNode("site","version");?></h1>
					<p><a href='../account/logout.php' target="_top">Logout</a> | <a href='../' target="_top">View live storefront</a></p>
				</div>
				</body>
			  </html><?php
		} elseif ($_GET['frame'] == "main") {
			//Main Frame
			?><html>
				<head><link href="style-main.css" rel="stylesheet" type="text/css" /></head>
				<body>
				<h1>Flumpshop Admin CP</h1>
				<p>PHP v<?php echo PHP_VERSION;?></p>
				<p>Database v<?php echo $dbConn->version();?>
                <div id='dialog'></div>
				</body>
			  </html><?php
		}
	} else {
		?><html><head><title>Flumpshop | Admin CP</title></head>
			<frameset cols="252px,*" framespacing="0" border="0" frameborder="0" frameborder="no" border="0">
			<frame name="leftFrame" id="leftFrame" src="?frame=leftFrame" scrolling="yes" frameborder="0" marginwidth="0" marginheight="0" border="no" />
			<frameset rows="60px,*" framespacing="0" border="0" frameborder="0" frameborder="no" border="0">
				<frame src="?frame=header" name="header" id="header" scrolling="no" noresize="noresize" frameborder="0" marginwidth="10" marginheight="0" border="no" />
				<frame src="?frame=main" name="main" id="main" scrolling="yes" frameborder="0" marginwidth="10" marginheight="10" border="no" />
			</frameset>
		  </frameset>
		  </html><?php
	}
}
?>