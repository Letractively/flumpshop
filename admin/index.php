<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../preload.php";

//Secure Redirect
if ($config->getNode('secure','admin') and $_SERVER['HTTPS'] == "off") {
	header("Location: ".$config->getNode('paths','secureRoot')."/admin");
	exit;
}

//Process Login

if (isset($_POST['uname'])) {
	$fail = false;
	$result = $dbConn->query("SELECT * FROM `acp_login` WHERE uname='".htmlentities($_POST['uname'],ENT_QUOTES)."' LIMIT 1");
	if ($dbConn->rows($result) == 0) {
		$fail = true;
	} else {
		$row = $dbConn->fetch($result);
		if ($row['pass'] != md5(sha1($_POST['pass']))) {
			$fail = true;
		} else {
			$dbConn->query("UPDATE `acp_login` SET last_login='".$dbConn->time()."' WHERE id=".$row['id']." LIMIT 1");
			$_SESSION['acpusr'] = base64_encode($row['uname']."~".sha1($row['pass']));
			if (strtotime($row['pass_expires']) <= time()) {
				header('Location: change_password.php');
			} else {
				header("Location: ./");
			}
		}
	}
}

//Not Logged In
if (!acpusr_validate()) {
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
<tr><td><label for='uname'>Username: </label></td><td><input type="text" name="uname" id="uname" /></td></tr>
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
<script type="text/javascript" language="javascript" src="../js/jqueryui.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/defaults.php"></script>
</head>
<body>
    <center><img src="images/logo.jpg" />
    <div class="header">flump<span class='header2'>shop</span> <?php echo $config->getNode("site","version");?></div>
    Powered by Flumpnet<br /><br />
    <div id="navContainer">
    <div id="navAccordion">
	<?php
	if (
		acpusr_validate('can_add_categories') or
		acpusr_validate('can_edit_categories') or
		acpusr_validate('can_delete_categories') or
		acpusr_validate('can_add_products') or
		acpusr_validate('can_edit_products') or
		acpusr_validate('can_delete_products')
	) {
	?>
	<h3>Manage Catalogue</h3>
		<div>
			<?php if (acpusr_validate('can_add_categories') or acpusr_validate('can_edit_categories') or acpusr_validate('can_delete_categories')) { //Category Manager?>
				<a href='endpoints/switchboard/categories.php' onclick='loader("Loading Category Menu...");' target='main'>Manage Categories</a>
				<?php
			}
			if (acpusr_validate('can_add_products') or acpusr_validate('can_edit_products') or acpusr_validate('can_delete_products')) { //Product Manager?>	
			<a href='endpoints/switchboard/products.php' onclick='loader("Loading Products Menu...");' target='main'>Manage Products</a>
			<?php
			}
			?>
		</div>
		<?php
		}
		if (
		acpusr_validate('can_post_news') or
		acpusr_validate('can_edit_pages')
		) {
		?>
		<h3>Manage Site</h3>
			<div>
			<?php if (acpusr_validate('can_post_news') or acpusr_validate('can_edit_pages')) { //News Manager?>
				<a href='endpoints/switchboard/news.php' onclick='loader("Loading News Menu...");' target='main'>Manage News</a>
			<?php
			}
			if (acpusr_validate('can_edit_pages')) { //Page Manager
			?>
				<a href='endpoints/switchboard/pages.php' onclick='loader("Loading Pages Menu...");' target='main'>Manage Pages</a>
				<a href='endpoints/switchboard/messages.php' onclick='loader("Loading Messages Menu...");' target='main'>Manage Messages</a>
			<?php
			}?>
			</div>
		<?php
		}
		
		if (
			acpusr_validate('can_create_orders') or
			acpusr_validate('can_view_orders') or
			acpusr_validate('can_edit_users')
		) {
			?>
			<h3>Manage Sales</h3>
			<div>
				<a href='endpoints/switchboard/orders.php' onclick='loader("Loading Orders Menu...");' target="main">Manage Orders</a>
				<a href='endpoints/switchboard/offers.php' onclick='loader("Loading Offers Menu...");' target="main">Manage Offers</a>
			</div>
			<?php
		}
		?>
		<h3>Clients</h3>
		<div>
			<a href="endpoints/clients/listCustomers.php" onclick='loader("Loading Content...");' target="main">Customer Manager</a>
			<a href="endpoints/clients/addCustomers.php" onclick='loader("Loading Content...");' target="main">Import Customer Data</a>
		</div>
        <h3>Deliveries</h3>
        <div>
        	<a href="endpoints/delivery/countries.php" onClick="loader('Loading Content...');" target="main">Supported Countries</a>
            <a href="endpoints/delivery/deliveryRates.php" onClick="loader('Loading Content...');" target="main">Delivery Rates</a>
        </div>
		<h3>Reports</h3>
		<div>
			<a href='endpoints/reports/customerReport.php' onclick='loader("Generating Report...");' target="main">Customer Report<sup>labs</sup></a>
			<a href='endpoints/reports/duplicates.php' onclick='loader("Generating Report...");' target="main">Content Suggestions<sup>labs</sup></a>
		</div>
        <h3>Advanced</h3>
        <div>
			<a href="endpoints/advanced/varMan.php" onclick='loader("Loading Content...");' target="main">Configuration Manager</a>
            <a href="endpoints/advanced/clearCache.php" onclick='loader("Clearing Cache...");' target="main">Clear Cache</a>
			<a href="endpoints/process/cron.php" onclick='loader("Executing Cron Script...");' target="main">Cron Script</a>
			<a href="endpoints/advanced/execute.php" onclick='loader("Loading Content...");' target="main">Execute PHP</a>
			<a href="endpoints/advanced/query.php" onclick='loader("Loading Content...");' target="main">Execute SQL</a>
			<a href="endpoints/advanced/export.php" onclick='loader("Loading Content...");' target="main">Export</a>
			<a href="endpoints/advanced/bugs.php" onclick='loader("Loading Content...");' target="main">Feedback</a>
            <a href="endpoints/advanced/upload.php" onclick='loader("Loading Content...");' target="main">File Upload</a>
			<a href="endpoints/advanced/import.php" onclick='loader("Loading Content...");' target="main">Import</a>
            <a href="logs" onclick='loader("Loading Content...");' target="main">Log Viewer</a>
            <a href="endpoints/advanced/phpinfo.php" onclick='loader("Loading Content...");' target="main">PHP Info</a>
            <a href="endpoints/advanced/recreateImages.php" onclick='loader("Loading Content...");' target="main">Rebuild Images</a>
			<a href="endpoints/advanced/userManager.php" onclick='loader("Loading Content...");' target="main">User Manager</a>
			<a href="endpoints/advanced/updateKeywords.php" onclick='loader("Generating Keywords...");' target="main">Auto-Generate Keywords</a>
        </div>
        <h3>Plugins</h3>
        <div>
        	<a href="endpoints/plugins/plugins.php" onclick='loader("Loading Content...");' target="main">Plugin Manager</a><?php
			//Each plugin that has /endpoints/index.php will have an option displayed here
			$dir = opendir($config->getNode('paths','offlineDir')."/plugins");
			while ($module = readdir($dir)) {
				if (file_exists($config->getNode('paths','offlineDir')."/plugins/".$module."/endpoints/index.php")) {
					echo '<a href="endpoints/plugins/pluginProvider.php?mod='.$module.'&page=index" onclick=\'loader("Loading Content...");\' target="main">'.$module.'</a>';
				}
			}
        ?></div>
		<h3>Credits</h3>
		<div>Developed by Flumpnet. The system makes use of the TinyMCE Editor, jQuery and the jQueryUI Framework, and FPDF (+Sphider?)</div>
    </div>
    </div>
    </center>
    <script type="text/javascript">
    $(document).ready(function() {
							   $('#navAccordion').accordion({collapsible: true, active: false, autoHeight: false, icons: {'header': 'ui-icon-circle-arrow-e', 'headerSelected': 'ui-icon-circle-arrow-s'}});
							   });
	function loader(str) {
		$(parent.main.document.getElementById('dialog')).html(loadMsg(str));
		if (typeof(parent.main.loadDialog) == "function") {
			parent.main.loadDialog();
		}
		return false;
	}
    </script>
</body>
</html><?php
		} elseif ($_GET['frame'] == "header") {
			//Header Frame
			?><html>
				<head><link href="style-header.css" rel="stylesheet" type="text/css" /></head>
				<body>
				<h1 class="title">ADMINISTRATOR CONTROL PANEL</h1>
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
				<head><link href="style-main.css" rel="stylesheet" type="text/css" /><link href="jqueryui.css" rel="stylesheet" type="text/css" /><script src="../js/jquery.js"></script><script src="../js/jqueryui.js"></script><script>function loadDialog() {$('#dialog').dialog();}</script></head>
				<body>
				<h1>Flumpshop v<?php echo $config->getNode('site','version');?></h1>
                <h2>Admin CP</h2><?php
				//Check for possible security issues
				if (file_exists("setup")) {
					echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span><strong>Security Issue</strong> - After initial installation, it is recommended that your rename or delete the /admin/setup folder in order to increase security.</div>";
				}
				
				//Check for Updates
				if (!$latestVer = file_get_contents("http://flumpshop.googlecode.com/svn/updater/version.txt")) {
					echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-extlink'></span><strong>Connection Failure</strong> - An error occured checking for updates.</div>";
				} elseif ($config->getNode('site','version') != $latestVer) {
					echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-notice'></span><strong>Update Available</strong> - An update is available for installation. To install, click the Upgrade Wizard button above.</div>";
				}
				
				//Check for unread feedback
				$result = $dbConn->query("SELECT * FROM `bugs` WHERE resolved = 0");
				if ($dbConn->rows($result) != 0) {
					echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-notice'></span><strong>New Feedback</strong> - Unread Feedback is available in Advanced->Feedback</div>";
				}
				?><div id='dialog'></div>
				<h2>Getting Started Checklist</h2>
				<p>Just starting out on setting up your own website? Here's a little checklist to get you started.</p>
				<ul>
					<li><strong>Create your own login</strong> - The inituser account won't last forever, so head over to Advanced->User Manager to set up a permanent account for yourself and anyone else who you want to be able to administer your account.</li>
					<li><strong>Add some categories</strong> - Create sections for items to be in. You can do this later, but it helps to keep things organised right from the start.</li>
					<li><strong>Add some products</strong> - Start adding your products to the site. With the new Manage Products feature, doing this is an absolute breeze.</li>
					<li><strong>Post some news</strong> - At the moment, there's only placeholder content for some sections of your site. Make sure that if they're turned on, that they are customised.
						<ul>
							<li>Home Page News (News->New News Post)</li>
							<li>Home Page Technical Tips (News->New Technical Tips Post)</li>
							<li>Home Page Content (Pages->Home Page)</li>
							<li>About Page Content (Pages->About Page)</li>
							<li>Contact Page Content (Pages->Contact Page)</li>
						</ul>
					</li>
				</ul>
				<p>Those are all the basics to get a site up and running. If you're selling online, make sure to take a look over in the deliveries section to to set delivery costs.</p>
				<p>Remember, Flumpshop is a fully featured management system. Import existing customers and orders, and the site can greatly improve your business efficiency with a centralised location for all your information, but you must first take the time out to add all this information.</p>
				</body>
			  </html><?php
		}
	} else {
		//Frames not sent yet
		if (!isset($_SESSION['acpLoaded'])) {
			//Load resources, particularly for TinyMCE
			include 'loader.php';
		} else {
			//Ready to send frames
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
}
?>