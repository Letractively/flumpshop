<?php $_SETUP = true; require_once("../../preload.php"); error_reporting(E_ALL);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='keywords' content='' />
<meta name='description' content='' />
<title>Flumpshop Setup</title>
<link rel='stylesheet' href='../../style/style.css' type='text/css' />
<link rel='stylesheet' href='../../style/style-subpage.css' type='text/css' />
<link rel='stylesheet' href='../../style/style_carousel.php' type='text/css' />
<script src='../../js/jquery.js' type='text/javascript'></script>
<script type="text/javascript" src="../../js/jcarousel.js"></script> 
<script type="text/javascript" src="../../js/jqueryui.js"></script> 
<script type="text/javascript" src="../../js/jeditable.js"></script> 
<script type="text/javascript" src="../../js/jquery.validate.min.js"></script> 
<script type="text/javascript" src="../../js/jquery.validate.password.js"></script> 
<script type="text/javascript" src="../../js/jquery.form.js"></script>
<link rel="stylesheet" type="text/css" href="../../style/jcarousel.css" /> 
<link rel="stylesheet" type="text/css" href="../../style/skins/rjc/skin.php" />
<link rel="stylesheet" type="text/css" href="../../style/jquery.css" />
<link rel="stylesheet" type="text/css" href="../../style/jquery-overrides.css" />
</head>
<body>
<div id='header'>
<div id="title">
<a href='javascript:alert("Sorry! You have to finish the setup wizard before I can let you do that!");'><h1>Wizard!</h1>
<h1 class='drop_shadow'>Wizard!</h1>
<h1 class='slogan'>Flumpnet: The living online shop</h1></a>
</div>
</div>
<!--End Header-->
<div id="content_container_background">
<div id="content_container">
  <div id="mainContent">
<?php  if ($_PRINTDATA) echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-alert'></span>Debug Mode Enabled.</div>";?>

<h1 class="content">Setup</h1>
  <?php
	if (file_exists(dirname(__FILE__)."/../conf.txt") && !(isset($_SESSION['adminAuth']) && $_SESSION['adminAuth'] == true)) {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>You must enter the System Administrator password before you can run the setup wizard.</div><div class='ui-state-highlight'><span class='ui-icon ui-icon-info'></span>Forgotten the password? Delete conf.txt in the root directory of the site to reset.</div>";
	} else {
		//Setup Logger
		$setupLog = fopen(dirname(__FILE__)."/../logs/setup.log","a+");
		function setup_log($msg) {
			global $setupLog;
			if (is_resource($setupLog))
			fwrite($setupLog,$msg."\r\n");
		}
		
		//Database Upgrader
		function DBUpgrade($current_version = 1) {
			global $dbConn;
			$current_version++;
			while (file_exists(dirname(__FILE__)."/sql/DBUpgrade_v".$current_version.".sql")) {
				$qry = implode("",file(dirname(__FILE__)."/sql/DBUpgrade_v".$current_version.".sql")); //file_get_contents added in 4.3
				$dbConn->multi_query($qry);
				$dbConn->query("UPDATE `stats` SET value = '$current_version' WHERE `key`='dbVer' LIMIT 1");
				setup_log("DBUpgrade: Database upgraded to v$current_version.");
				$current_version++;
			}
		}
		
		//Section input form
		function input_form($tree,$nextstage,$required = false) {
			if ($required) $class = "required "; else $class = "";
			global $config;
			echo "<form action='?stage=$nextstage' method='post' class='ui-widget-content'><table>";
				foreach ($config->getNodes($tree) as $pathNode) {
					$name = $config->getFriendName($tree,$pathNode);
					$value = $config->getNode($tree,$pathNode);
					if (is_bool($value)) {
						if ($value == true) $checked = " checked='checked'"; else $checked = "";
						echo "<tr class='ui-widget-content'><td><label for='$pathNode'>$name</label></td><td><input type='checkbox' name='$pathNode' id='$pathNode' class='ui-state-default'$checked /></td></tr>";
					} else {
						$value = str_replace("'","&apos;",htmlentities($value));
						$class .= get_valid_class($tree,$pathNode);
						echo "<tr class='ui-widget-content'><td><label for='$pathNode'>$name</label></td><td><input type='text' name='$pathNode' id='$pathNode' value='$value' class='".$class."ui-state-default' /></td></tr>";
					}
				}
				echo '</table><input type="submit" value="Next -&gt;" class="ui-state-default" /></form>';
		}
		
		if (!isset($_GET['stage'])) {
			//Analyse System Configuratiion
			$success = array();
			$fail = array();
			$warn = array();
			/*Log directory permissions*/
			if (!is_writable(dirname(__FILE__)."/../logs/setup.log")) {
				$warn[] = "I don't THINK I have access to the {siteroot}/admin/logs directory. I need write permissions here to store logs from various aspects of the site, including temporary data for this wizard";
			} else {
				$success[] = "I can write to the {siteroot}/admin/logs directory";
			}
			/*Root directory permissions*/
			if (!is_writable(dirname(__FILE__)."/../../conf.txt")) {
				$warn[] = "I don't THINK I have access to the {siteroot} directory. The setup wizard needs write access for the configuration process. After setup, you should change this to read-only";
			} else {
				$success[] = "I can write to the {siteroot} directory";
			}
			/*PHP Version*/
			if (PHP_VERSION < "4.4.9") {
				$fail[] = "PHP Version 4.4.9 or newer is required me to work. Please make sure the server has this installed before continuing";
			} else {
				if (PHP_VERSION >= "5.3.0") {
					$success[] = "PHP Version 5.4.9 or newer is installed (".PHP_VERSION.")";
				} else {
					$warn[] = "PHP v".PHP_VERSION." is supported, but it is recommended that you have version 5.3.0 or higher.";
				}
			}
			/*MySQLi Extension*/
			if (!extension_loaded("mysqli")) {
				$warn[] = "The MySQLi extension is not installed. This extension is required for MySQL database access";
			} else {
				$success[] = "The MySQLi extension is installed and loaded";
			}
			/*Curl Extension*/
			if (!extension_loaded("curl")) {
				$warn[] = "The CURL extension is not installed. This extension is required for the PayPal API";
			} else {
				$success[] = "The CURL extension is installed and loaded";
			}
			/*SimpleXML Extension*/
			if (!extension_loaded("simplexml")) {
				if (!dl("simplexml")) {
					$warn[] = "The SimpleXML extension is not installed. Database logs will be disabled.";
				}
			} else {
				$success[] = "The SimpleXML extension is installed and loaded";
			}
			/*GD Extension*/
			if (!extension_loaded("gd")) {
				$fail[] = "The GD extension is not installed. This extension is required for image manipulation";
			} else {
				$success[] = "The GD extension is installed and loaded";
			}
			/*Fileinfo Extension*/
			if (!extension_loaded("fileinfo")) {
				if (!dl("fileinfo")) {
					if (PHP_VERSION >= "5.3.0") {
						$fail[] = "The Fileinfo extension is not installed. This extension is required for general file purposes";
					} else {
						$warn[] = "The Fileinfo extension is not installed. Some functionality may be unavailable. It is required if you upgrade to PHP 5.3 later.";
					}
				}
			} else {
				$success[] = "The Fileinfo extension is installed and loaded";
			}
			/*SQLite3 Extension*/
			if (!extension_loaded("sqlite")) {
				$warn[] = "The SQLite extension is not installed. This extension is required for SQLite Database Support";
			} else {
				$success[] = "The SQLite extension is installed and loaded";
			}
			?>
			<h1>Welcome!</h1>
			<p>Before you can launch your new e-shop, your system needs to be configured for use. Please review the details of my analysis below.</p>
			<?php
			if (!sizeof($fail) == 0) {
				echo "<div class='ui-widget ui-state-error'><div class='ui-widget-header ui-state-error'>I've found the following problem(s) with your server environment</div><div class='ui-widget-content ui-state-error'>";
				foreach ($fail as $failure) {
					setup_log("Error: $failure");
					echo $failure."<br />";
				}
				echo "</div></div><br />";
			}
			if (!sizeof($warn) == 0) {
				echo "<div class='ui-widget ui-state-highlight'><div class='ui-widget-header'>These COULD be a problem...</div><div class='ui-widget-content ui-state-disabled'>";
				foreach ($warn as $win) {
					setup_log("Warning: $win");
					echo $win."<br />";
				}
				echo "</div></div><br />";
			}
			if (!sizeof($success) == 0) {
				echo "<div class='ui-widget ui-state-default'><div class='ui-widget-header'>I can't find any problems with these</div><div class='ui-widget-content'>";
				foreach ($success as $win) {
					setup_log("Pass: $win");
					echo $win."<br />";
				}
				echo "</div></div><br />";
			}
			if (!sizeof($fail) == 0) {
				echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-alert'></span>I need the above issue(s) fixed before I can continue!</div>";
			} else {
				echo "<a href='?stage=1'><div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>It looks like everything checks out. Click here to continue</div></a>";
			}
		} else {
			$stage = $_GET['stage'];
			if (file_exists(dirname(__FILE__)."/../logs/temp_conf.txt")) $config = unserialize(file_get_contents(dirname(__FILE__)."/../logs/temp_conf.txt"));
			switch ($stage) {
				case 1:
					//Prevent Blank Page
					if (file_exists(dirname(__FILE__)."/../logs/temp_conf.txt")) {
						unlink(dirname(__FILE__)."/../logs/temp_conf.txt");
						header("Location: ?stage=1");
						die();
					}
					setup_log("Stage 1: Generating Default Configuration");
					/*First Stage*/
					//Build Default Config Object
						$config = new Config("Default Configuration",$INIT_DEBUG);
	
						//Main Tree
						$config->addTree("site", "Main Settings");
						
						$config->setNode("site", "enabled", true, "Enable Site");
						$config->setNode("site", "password", md5("123456"), "Admin Password");
						$config->setNode("site", "vat", 17.5, "VAT Rate");
						$config->setNode("site", "country", "GB", "Default Country");
						$config->setNode("site", "shopMode", false, "Shop Mode");
						$config->setNode("site", "homeTab", true, "Home Tab");
						$config->setNode("site", "sendFeedback", true, "Send Feedback");
						$config->setNode("site", "version", "0.9.232", "Version");
						$config->setNode("site", "loginTab", true, "Login Tab");
						
						//Paths and Directories Tree
						$config->addTree("paths", "Site Paths and Directories");
						
						$config->setNode("paths", "root", "http://".$_SERVER['HTTP_HOST'].preg_replace('/\/admin\/setup\/\?stage=.*/i','',$_SERVER['REQUEST_URI']), "Home URL");
						$config->setNode("paths", "secureRoot", "https://".$_SERVER['HTTP_HOST'].preg_replace('/\/admin\/setup\/\?stage=.*/i','',$_SERVER['REQUEST_URI']), "Secure Home URL");
						$config->setNode("paths", "path", preg_replace("/(\\\\|\/)admin(\\\\|\/)setup(.*)/i","",dirname(__FILE__)), "Home Directory");
						$config->setNode("paths", "offlineDir", "C:/inetpub/data", "Offline Directory");
						$config->setNode("paths", "logDir", dirname(__FILE__)."/../logs", "Server Log Directory");
						
						//Secure Site
						$config->addTree("secure", "Secure Site Settings");
						
						$config->setNode("secure", "enabled", true, "Enable Secure Transaction Processing");
						$config->setNode("secure", "admin", true, "Force Secure Admin CP");
						
						//Database
						$config->addTree("database", "Database Server Settings");
						
						$config->setNode("database", "type", "mysql", "mysql/sqlite - Experimental");
						$config->setNode("database", "address", "127.0.0.1", "Server Address");
						$config->setNode("database", "port", "3306", "Server Port");
						$config->setNode("database", "uname", "", "Username");
						$config->setNode("database", "password", "", "Password");
						$config->setNode("database", "name", "ecommerce", "Main Site Database Name");
						
						//Advanced Server
						$config->addTree("server", "Advanced Server Configuration");
						
						$config->setNode("server", "rewrite", false, "Enable URL Rewrite");
						$config->setNode("server", "holdTimeout", 60, "Item Hold Timeout");
						$config->setNode("server", "commitPayments", false, "Commit Payments (Debug)");
						$config->setNode("server", "crawlerAgents", "Googlebot|msnbot|Slurp", "Web Crawler Useragents");
						$config->setNode("server", "debug", true, "Enable Debug Mode");
						$config->setNode("server", "cronFreq", 30, "Cron Frequency (mins)");
						$config->setNode("server", "backupFreq", 48, "Backup Frequency (hrs)");
						
						//Messages
						$config->addTree("messages", "Predefined Text Strings");
						
						$config->setNode("messages", "footer", "Designed and built by Flumpnet", "Page Footer");
						$config->setNode("messages", "adminDenied", "You must enter the administrator password in the Site Admin section before you can perform this action.", "Admin Access Denied");
						$config->setNode("messages", "crawler", "A Crawler User Agent has been detected. Some site features are disabled to reduce server load.", "Crawler Agent");
						$config->setNode("messages", "maintenance", "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>The site has been shut down for maintenance unexpectedly.</div>", "Site Disabled");
						$config->setNode("messages", "name", "R&amp;middot;J&amp;middot;C Com", "Site Name");
						$config->setNode("messages", "tagline", "Developer Preview", "Tagline");
						$config->setNode("messages", "keywords", "RJC Commercial Catering equipment", "Keywords");
						$config->setNode("messages", "defaultCategoryName", "Uncategorised", "Default Category Name");
						$config->setNode("messages", "defaultCategoryDesc", "Details for this category are unavailable.", "Default Category Description");
						$config->setNode("messages", "basketRemItemConf", "Are you sure you want to remove this item from your basket?", "Remove from Basket");
						$config->setNode("messages", "basketEmptyConf", "Are you sure you want to empty your basket?", "Empty Basket");
						$config->setNode("messages", "noScript", "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Please enable JavaScript for this page to function properly.</div>", "JavaScript Disabled");
						$config->setNode("messages", "insufficientStock", "There is insufficient stock for this item. Please reduce the size of your order or try again later.", "Insufficient Stock");
						$config->setNode("messages", "featuredItemHeader", "Featured Item", "Featured Item Header");
						$config->setNode("messages", "popularItemHeader", "Most Popular", "Popular Item Header");
						$config->setNode("messages", "latestNewsHeader", "Latest News", "Latest News Header");
						$config->setNode("messages", "technicalHeader", "Technical Tips", "Technical Help Header");
						$config->setNode("messages", "transactionCancelled", "<h1>Transaction Cancelled</h1><p>You cancelled the purchase before it was completed.</p>","Transaction Cancelled");
						$config->setNode("messages", "transactionFailed", "The payment server reported that the transaction did not complete succesfully. Please try again later.","Transaction Failed");
						$config->setNode("messages", "paymentComplete", "Your payment is being processed by PayPal and you will receive e-mail confirmation shortly. Your order has now been stored in our database and you will receive an additional e-mail once the item(s) have been dispatched.","Payment Complete");
						
						$config->setNode("messages", "ajax500", "An 500 Internal Server error occured when trying to load a remote endpoint.", "AJAX 500 Error");
						$config->setNode("messages", "ajax404", "A remote endpoint was not found.", "AJAX 404 Error");
						$config->setNode("messages", "ajaxError", "An unknown error was encountered loading a remote endpoint.", "AJAX Error");
						
						//Pagination Messages
						$config->setNode("messages", "firstPage", "&lt;&lt;", "First Page Link");
						$config->setNode("messages", "lastPage", "&gt;&gt;", "Last Page Link");
						$config->setNode("messages", "previousPage", "&lt;", "Previous Page Link");
						$config->setNode("messages", "nextPage", "&gt;", "Next Page Link");
						
						//User Message
						$config->setNode("messages", "userNoCustomer", "You haven't given me any contact information yet. Do you want to do this now?", "No Contact Details");
						$config->setNode("messages", "loginNeeded", "You must be logged in to perform that action.", "Login Required");
						$config->setNode("messages", "countryNotSupported", "Sorry, but we can't deliver to your country online. We're working hard to expand our range, but you must call or email us to arrange delivery to this region.", "Unsupported Country");
						
						//Legal Additions
						$config->setNode("messages", "email", "support@".$_SERVER['HTTP_HOST'], "Email Address");
						$config->setNode("messages", "address", "24 Pendwyallt Road, BURNSIDE, EH52 8BS", "Address");
						
						//Full Page Content
						$config->setNode("messages", "404", 
<<<EOT
<h1>Not Being Seen</h1>
<p>I'm really sorry. I've looked high and low, left and right, but the page you've asked me to magic upon your screen just won't happen. Make sure you've asked me to look in the right place, and if you clicked a button <a href="../reportbug.php">click this one</a> too, and tell me what didn't work.</p>
<p>If all else fails, then you can assume that the page is currently on holiday, or is getting very good at not being seen.</p>
EOT
, "404 Error Page");
						$config->setNode("messages", "homePage", 
<<<EOT
We have some spectacular products with excellent sales and after-sales service, browse through our products - list the product codes and forward the information to us and we will be delighted to send you a formal quotation. If you would like extra information, brochures, drawings etc - please ask. 

We look forward to hearing from you.


We pride ourselves in providing high-quality equipment with First Class aftercare service.
EOT
/*PUBLIC RELEASE VALUE:
Hello, it's me again, the Flumpnet robot. I'm afraid there's three things I need to point out to you:

This is another one of my magical placeholder messages. This means that as soon as you tell me to put something here, then this message will just fade away forever. To do this, simply log in to the Administrator Control Panel, and click Home Page in the Edit Object section.
It sounds great, right? That all you have to do is click a few buttons instead of spending hours digging through the thousands and thousands of lines of code the make this website, and more importantly, me, happen. Well, you know how this is a Developer Preview release? And how this means that I'm still being developed? That's right. You've got it. Trying to edit the Home Page will merely result in the dreaded ERR_FEATURE_NOT_IMPLEMENTED message.
I know what you were thinking when you saw the three. Yeah. I'm intelligent enough to know there was actually going to be three of them. Anyway, on with the point. As with a large portion of me (the default is around 25KB at the time this was written), the content of this page is stored in the wonderful conf.txt file, which means it can be accessed and edited using the Variable Manager. But shh. It's a secret.
*/
, "Home Page");
						$config->setNode("messages", "privacyPolicy",
<<<EOT
<p>Hey, it's the Flumpnet robot. It is important that you know that the information given below is preliminary, and in some areas incomplete, and subject to change. Thought you ought to know.</p>
<p>This privacy policy sets out how [[name]] uses and protects any information that you give [[name]] when you use this website.</p> 
 
<p>[[name]] is committed to ensuring that your privacy is protected. Should we ask you to provide certain information by which you can be identified when using this website, then you can be assured that it will only be used in accordance with this privacy statement.</p> 
 
<p>[[name]] may change this policy from time to time by updating this page. You should check this page from time to time to ensure that you are happy with any changes. This policy is effective from 31/01/2010.</p> 
 
<h3 class='content'>What we collect</h3> 
We may collect the following information:
<ul> 
<li>name and job title</li> 
<li>contact information including email address</li> 
<li>demographic information such as postcode, preferences and interests</li> 
<li>other information relevant to customer surveys and/or offers</li> 
</ul> 
<h3 class='header'>What we do with the information we gather</h3> 
<p>We require this information to understand your needs and provide you with a better service, and in particular for the following reasons:</p> 
<ul> 
<li>Internal record keeping.</li> 
<li>We may use the information to improve our products and services.</li> 
<li>We may periodically send promotional emails about new products, special offers or other information which we think you may find interesting using the email address which you have provided.</li> 
<li>From time to time, we may also use your information to contact you for market research purposes. We may contact you by email, phone, fax or mail. We may use the information to customise the website according to your interests.</li> 
</ul> 
<h3 class='content'>Security</h3> 
<p>We are committed to ensuring that your information is secure. In order to prevent unauthorised access or disclosure we have put in place suitable physical, electronic and managerial procedures to safeguard and secure the information we collect online.</p> 
 
<h3 class="content">How we use cookies</h3> 
<p>A cookie is a small file which asks permission to be placed on your computer's hard drive. Once you agree, the file is added and the cookie helps analyse web traffic or lets you know when you visit a particular site. Cookies allow web applications to respond to you as an individual. The web application can tailor its operations to your needs, likes and dislikes by gathering and remembering information about your preferences.</p> 
 
<p>We use traffic log cookies to identify which pages are being used. This helps us analyse data about webpage traffic and improve our website in order to tailor it to customer needs. We only use this information for statistical analysis purposes and then the data is removed from the system.</p> 
 
<p>Overall, cookies help us provide you with a better website, by enabling us to monitor which pages you find useful and which you do not. A cookie in no way gives us access to your computer or any information about you, other than the data you choose to share with us.</p> 
 
<p>You can choose to accept or decline cookies. Most web browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies if you prefer. This may prevent you from taking full advantage of the website.</p> 
 
<h3 class="content">Links to other websites</h3> 
<p>Our website may contain links to other websites of interest. However, once you have used these links to leave our site, you should note that we do not have any control over that other website. Therefore, we cannot be responsible for the protection and privacy of any information which you provide whilst visiting such sites and such sites are not governed by this privacy statement. You should exercise caution and look at the privacy statement applicable to the website in question.</p> 
 
<h3 class="content">Controlling your personal information</h3> 
<p>You may choose to restrict the collection or use of your personal information in the following ways:</p> 
<ul> 
<li>whenever you are asked to fill in a form on the website, look for the box that you can click to indicate that you do not want the information to be used by anybody for direct marketing purposes</li> 
<li>if you have previously agreed to us using your personal information for direct marketing purposes, you may change your mind at any time by writing to or emailing us at <a href='mailto:[[email]]'>[[email]]</a></li> 
<li>We will not sell, distribute or lease your personal information to third parties unless we have your permission or are required by law to do so. We may use your personal information to send you promotional information about third parties which we think you may find interesting if you tell us that you wish this to happen.</li>
</ul> 
<p>You may request details of personal information which we hold about you under the Data Protection Act 1998. A small fee will be payable. If you would like a copy of the information held on you please write to [[address]].</p> 
 
<p>If you believe that any information we are holding on you is incorrect or incomplete, please write to or email us as soon as possible, at the above address. We will promptly correct any information found to be incorrect.</p>
EOT
,"Privacy Policy");
						$config->setNode("messages", "disclaimer",
<<<EOT
<p>The information contained in this website is for general information purposes only. The information is provided by [[name]] and while we endeavour to keep the information up to date and correct, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly at your own risk.</p>

<p>In no event will we be liable for any loss or damage including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever arising from loss of data or profits arising out of, or in connection with, the use of this website.</p>

<p>Through this website you are able to link to other websites which are not under the control of [[name]]. We have no control over the nature, content and availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.</p>

<p>Every effort is made to keep the website up and running smoothly. However, [[name]] takes no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond our control.</p>
EOT
,"Disclaimer");
						$config->setNode("messages", "termsConditions",
<<<EOT
<p>Welcome to our website. If you continue to browse and use this website you are agreeing to comply with and be bound by the following terms and conditions of use, which together with our privacy policy govern [[name]]'s relationship with you in relation to this website.</p>

<p>The term '[[name]]' or 'us' or 'we' refers to the owner of this website. The term 'you' refers to the user or viewer of our website.</p>

<p>The use of this website is subject to the following terms of use:</p>
<ol>
<li>The content of the pages of this website is for your general information and use only. It is subject to change without notice.</li>
<li>Neither we nor any third parties provide any warranty or guarantee as to the accuracy, timeliness, performance, completeness or suitability of the information and materials found or offered on this website for any particular purpose. You acknowledge that such information and materials may contain inaccuracies or errors and we expressly exclude liability for any such inaccuracies or errors to the fullest extent permitted by law.</li>
<li>Your use of any information or materials on this website is entirely at your own risk, for which we shall not be liable. It shall be your own responsibility to ensure that any products, services or information available through this website meet your specific requirements.</li>
<li>This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, layout, look, appearance and graphics. Reproduction is prohibited other than in accordance with the copyright notice, which forms part of these terms and conditions.</li>
<li>All trade marks reproduced in this website which are not the property of, or licensed to, the operator are acknowledged on the website.</li>
<li>Unauthorised use of this website may give rise to a claim for damages and/or be a criminal offence.</li>
<li>From time to time this website may also include links to other websites. These links are provided for your convenience to provide further information. They do not signify that we endorse the website(s). We have no responsibility for the content of the linked website(s).</li>
<li>You may not create a link to this website from another website or document without [[name]]'s prior written consent.</li>
</ol>
<p>Your use of this website and any dispute arising out of such use of the website is subject to the laws of England, Scotland and Wales.</p>
EOT
, "Terms and Conditions");
						
						$config->setNode("messages", "aboutPage",
<<<EOT
We have some spectacular products with excellent sales and after-sales service, browse through our products - list the product codes and forward the information to us and we will be delighted to send you a formal quotation.

If you would like extra information, brochures, drawings etc - please ask. We look forward to hearing from you.

We pride ourselves in providing high-quality equipment with First Class aftercare service
EOT
/*Release Value:
<p>Hello, it's me again, the Flumpnet robot. I'm afraid there's three things I need to point out to you:</p>
<ol>
	<li>This is another one of my magical placeholder messages. This means that as soon as you tell me to put something here, then this message will just fade away forever. To do this, simply log in to the Administrator Control Panel, and click About Page in the Edit Object section.</li>
    <li>It sounds great, right? That all you have to do is click a few buttons instead of spending hours digging through the thousands and thousands of lines of code the make this website, and more importantly, me, happen. Well, you know how this is a Developer Preview release? And how this means that I'm still being developed? That's right. You've got it. Trying to edit the About Page will merely result in the dreaded ERR_FEATURE_NOT_IMPLEMENTED message.</li>
    <li>I know what you were thinking when you saw the three. Yeah. I'm intelligent enough to know there was actually going to be three of them. Anyway, on with the point. As with a large portion of me (the default is around 15KB at the time this was generated), the content of this page is stored in the wonderful conf.txt file, which means it can be accessed and edited using the Variable Manager. But shh. It's a secret.</li>
</ol>
*/
,"About Page");
						
$config->setNode("messages", "contactPage",
<<<EOT
RJC Commercial Food Equipment<br />
Brunel House,<br />
Old Great North Road,<br />
Sawtry,<br />
Huntingdon,<br />
Cambridgeshire,<br />
PE28 5XN.<br />
United Kingdom.<br />
<br />
Tel: +44 01487 830968<br />
Fax: +44 01487 830968  Two lines<br />
E-mail: sales@rjccom.co.uk<br />
EOT
/*Release Value:
<p>Hello, it's me again, the Flumpnet robot. I'm afraid there's three things I need to point out to you:</p>
<ol>
	<li>This is another one of my magical placeholder messages. This means that as soon as you tell me to put something here, then this message will just fade away forever. To do this, simply log in to the Administrator Control Panel, and click Contact Page in the Edit Object section.</li>
    <li>It sounds great, right? That all you have to do is click a few buttons instead of spending hours digging through the thousands and thousands of lines of code the make this website, and more importantly, me, happen. Well, you know how this is a Developer Preview release? And how this means that I'm still being developed? That's right. You've got it. Trying to edit the Contact Page will merely result in the dreaded ERR_FEATURE_NOT_IMPLEMENTED message.</li>
    <li>I know what you were thinking when you saw the three. Yeah. I'm intelligent enough to know there was actually going to be three of them. Anyway, on with the point. As with a large portion of me (the default is around 15KB at the time this was generated), the content of this page is stored in the wonderful conf.txt file, which means it can be accessed and edited using the Variable Manager. But shh. It's a secret.</li>
</ol>
*/
,"Contact Page");
						
						//Form Messages
						$config->setNode("messages", "formFieldRequired", "This field is required.", "Required Field");
						
						//Log Files
						$config->addTree("logs", "Server Log Files");
						
						$config->setNode("logs", "enabled", true, "Enable Server Logs");
						$config->setNode("logs", "errors", "errors.log", "Error Logfile");
						
						//Order Statuses
						$config->addTree("orderstatus", "Order Statuses");
						
						$config->setNode("orderstatus", "0", array("name" => "New", "active" => true), "Status ID #0");
						$config->setNode("orderstatus", "1", array("name" => "Waiting for Stock", "active" => true), "Status ID #1");
						$config->setNode("orderstatus", "2", array("name" => "Payment Rejected", "active" => false), "Status ID #2");
						$config->setNode("orderstatus", "3", array("name" => "Dispatched", "active" => false), "Status ID #3");
						$config->setNode("orderstatus", "4", array("name" => "Returning", "active" => true), "Status ID #4");
						$config->setNode("orderstatus", "5", array("name" => "Returned", "active" => false), "Status ID #5");
						$config->setNode("orderstatus", "6", array("name" => "Acknowledged", "active" => true), "Status ID #6");
						
						//PayPal
						$config->addTree("paypal","PayPal Settings");
						
						$config->setNode("paypal", "enabled", true, "Enable PayPal"); //Not Used
						$config->setNode("paypal", "uname", "", "PayPal API Username");
						$config->setNode("paypal", "pass", "", "PayPal API Password");
						$config->setNode("paypal", "apiKey", "", "PayPal API Key");
						
						//Pagination Options
						$config->addTree("pagination", "Pagination Options");
						
						$config->setNode("pagination", "sitemapPerPage", 10, "Per Page: Sitemap");
						$config->setNode("pagination", "categoryPerPage", 10, "Per Page: Category View");
						$config->setNode("pagination", "searchPerPage", 25, "Per Page: Search Results");
						$config->setNode("pagination", "editItemsPerPage", 50, "Per Page: Edit Items List");
						
						//Account Settings
						$config->addTree("account", "Account Settings");
						
						$config->setNode("account", "requireAllAtSignup", false, "Require All Details at Signup");
						$config->setNode("account", "requireEmailValidation", true, "Require Email Address Confirmation");
						$config->setNode("account", "validationTimeout", 48, "Email Validation Timeout (hrs)");
						
						//Mail Settings
						$config->addTree("smtp", "Mail Server Settings");
						
						$config->setNode("smtp", "host", "localhost", "SMTP Server");
						$config->setNode("smtp", "port", 25, "SMTP Port");
						$config->setNode("smtp", "uname", "daemon@wallis2012.gotdns.com", "Username");
						$config->setNode("smtp", "password", "Admin123", "Password");
						$config->setNode("smtp", "email", "daemon@wallis2012.gotdns.com", "Email Address");
						
						//Carousel Widget
						$config->addTree("widget_carousel", "Carousel Widget Settings");
						
						$config->setNode("widget_carousel", "onIndex", true, "Show on Home Page");
						$config->setNode("widget_carousel", "indexPosition", "right", "Position on Home Page");
						$config->setNode("widget_carousel", "indexHeight", 800, "Height on Home Page");
						$config->setNode("widget_carousel", "images", 10, "Number of Images");
						$config->setNode("widget_carousel", "imageScale", 1.5, "Image Scale");
						
						//Item View Options
						$config->addTree("viewItem", "Item View Settings");
						
						$config->setNode("viewItem", "showID", true, "Show Item ID");
						$config->setNode("viewItem", "imageScale", 2.5, "Image Scale");
						
						//Start First Stage
						echo "<p>Hello, I've just finished generating a sample configuration file for this site. Over the next few pages the information I need to run will need to be filled out. Let's start with something simple. Give me a name in the box below.</p><form action='?stage=2' method='post' class='ui-widget-content' id='jquery-form'><input type='text' name='siteName' id='siteName' class='ui-state-default required' value='".$config->getNode('messages','name')."' /><input type='submit' value='Next -&gt;' class='ui-state-default' /></form>";
					break;
				case 2:
					/*Second Stage*/
					//Process Recieved Data
					$config->setNode("messages","name",$_POST['siteName']);
					setup_log("Stage 2: Updated Configuration");
					//Start Second Stage
					echo "<p>".$_POST['siteName']."? That sounds great. Now that's the easy part over. Can you tell me where I am, both on the Internet, and physically, as well as where to put certain information?</p>";
					setup_log("Stage 2: Loading Input Form: Paths");
					input_form('paths',3,true);
					?>
					<div class="ui-state-highlight"><span class="ui-icon ui-icon-star"></span>Tip: Refer to my Installation Guide for more information about a variable</div>
					<?php
					break;
				case 3:
					/*Third Stage*/
					//Process Received Data
					$data = array_keys($_POST);
					$config->falseify("paths"); //Fix for checkbox bools
					foreach ($data as $key) {
						if (is_bool($config->getNode('paths',$key))) {
							if ($_POST[$key] == "on") $config->setNode('paths',$key,true);
							else $config->setNode('paths',$key,false);
						} else {
							$config->setNode('paths',$key,$_POST[$key]);
						}
					}
					setup_log("Stage 3: Updated Configuration");
					//Start Third Stage
					?>
					<p>Okay, thanks for the info. I'll be sure to use it on every page I load (except this one), so let's hope it was right. Next, I want to know where you want me to put all my data. I only know how to use MySQL (And Experimentally SQLite) Databases at the moment, so can you please make sure that's what you're giving me. Thanks :D.</p>
                    <div class="ui-state-highlight"><span class="ui-icon ui-icon-gear"></span>If you're using SQLite, use Address as the location for where I should keep my database file. Port, username, password and database name will be ignored.</div>
					<?php
					setup_log("Stage 3: Loading Input Form: Database");
					input_form('database',4,false);
					break;
				case 4:
					/*Fourth Stage*/
					//Process Received Data
					$data = array_keys($_POST);
					$config->falseify("database"); //Fix for checkbox bools
					foreach ($data as $key) {
						if (is_bool($config->getNode('database',$key))) {
							if ($_POST[$key] == "on") $config->setNode('database',$key,true);
							else $config->setNode('database',$key,false);
						} else {
							$config->setNode('database',$key,$_POST[$key]);
						}
					}
					setup_log("Stage 4: Updated Configuration");
					//Build Database Structure
					debug_message("Database Configuration Set. Testing Connection.");
					$dbConn = db_factory();
					if (!$dbConn->isConnected()) {
						setup_log("dbConn: Error - ".$dbConn->error());
						setup_log("Stage 4: Error - Database Connection Failed");
						debug_message("Database Connection Error.");
						echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Whoops! I can't seem to find your MySQL Database. Are you sure the connection settings are correct and the database has been created? No? <a href='?stage=3'>Click Here</a> to go back!</div>";
					} else {
						setup_log("Stage 4: Database Connected");
						debug_message("Database Connection Established. Generating Content.");
						//Create/Upgrade DB
						if ($result = $dbConn->query("SELECT `value` FROM `stats` WHERE `key` = 'dbVer' LIMIT 1")) {
							$result = $dbConn->fetch($result);
							debug_message("Database Version: ".$result['value']);
							setup_log("Stage 4: Existing installation detected. Passing to DBUpgrade.");
							DBUpgrade($result['value']);
						} else {
							debug_message("Initial Install Detected. Running install.sql.");
							setup_log("Stage 4: No exisiting database detected. Running install.sql");
							$dbConn->multi_query(file_get_contents(dirname(__FILE__)."/sql/install.sql"),true);
							setup_log("Stage 4: install.sql Executed. Passing to DBUpgrade.");
							DBUpgrade(1);
						}
						debug_message("Install SQL Code Executed.",true);
						setup_log("Stage 4: Executed Installation SQL File");
					}
					//Start Fourth Stage
					?>
					<p>Hey, that's really useful. I'm sure I can use that to keep track of everyone that visits and every button they click. Well, ever heard of PayPal? I like it very much, and I'd like to know some connection details for your PayPal account, if you have one. It's my default method of processing transactions, so make sure you get a clever developer to program and alternative if you don't have these details.</p>
					<?php
					setup_log("Stage 4: Loading Input Form: PayPal");
					input_form('paypal',5,false);
					break;
				case 5:
					/*Fifth Stage*/
					//Process Received Data
					$data = array_keys($_POST);
					$config->falseify("paypal"); //Fix for checkbox bools
					foreach ($data as $key) {
						if (is_bool($config->getNode('paypal',$key))) {
							if ($_POST[$key] == "on") $config->setNode('paypal',$key,true);
							else $config->setNode('paypal',$key,false);
						} else {
							$config->setNode('paypal',$key,$_POST[$key]);
						}
					}
					setup_log("Stage 5: Updated Configuration");
					//Start Fifth Stage
					?>
					<p>I almost forgot! I need you to tell me a secret password that'll let me make sure that you're you when you try to change settings. Just type it twice below!</p>
					<form action="./?stage=6" method="post" name='jquery-passform' id="jquery-passform"><table>
					<tr class="ui-widget-content">
						<td><label for="password1">Password: </label></td>
						<td><input type="password" class="ui-state-default required password" name="password1" id="password1" onKeyUp="$('#password1').valid();" /></td>
						<td>
							<div class="password-meter"> 
								<div class="password-meter-message">Strength-o-meter</div> 
								<div class="password-meter-bg"> 
									<div class="password-meter-bar"></div> 
								</div> 
							</div>
						</td>
					</tr>
					<tr class="ui-widget-content">
						<td><label for="password2">Confirm Password: </label></td>
						<td><input type="password" class="ui-state-default required" name="password2" id="password2" /></td>
					</tr>
					</table>
					<input type="submit" value="Next -&gt;" class="ui-state-default" />
					</form>
					<script type="text/javascript">
					$('#jquery-passform').validate({errorClass: "ui-state-highlight",
												   rules: {
													  password2: {
														  equalTo: "#password1"
													  }
												  },
												  messages: {
													  password2: {
														  equalTo: "Passwords must match!"
													  }
												  },
												  });
					</script>
					<?php
					setup_log("Stage 5: Loading Input Form: Administrator Password");
					break;
				case 6:
					/*Final Required Step*/
					//Process received data
					if ($_POST['password1'] != $_POST['password2']) {
						setup_log("Stage 6: Passwords do not match");
						echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Oops! It looks like your passwords don't match! You'll have to go back and put them in again. <a href='?stage=5'>Click Here</a> to go back!</div>";
						exit();
					}
					$config->setNode('site','password',md5($_POST['password1']));
					setup_log("Stage 6: Configuration Updated");
					?>
					<p>Thanks, remember this password, because if you forget it, you'll have to reset it by running this setup wizard again.</p>
					<p>I can now offer you a choice. That's the end of all the information required to start me up, so you can exit this wizard now. However, you may wish to customize the site further, by setting advanced information, such as debug modes, predefined strings, and cron jobs. If you don't know what these are, it's probably best if you leave now.</p>
					<div class="ui-state-highlight"><span class="ui-icon ui-icon-gear"></span>Notice: By default, the site is in disabled mode for you to add products and other information to the database. Until it is enabled only the Admin CP will work.</div>
					<input type="button" value="Next -&gt; (Security Settings)" class="ui-state-default" onclick="window.location = '?stage=8';" />
					<input type="button" value="Finish -&gt;" class="ui-state-default ui-state-highlight" onclick="window.location = '?stage=-1';" />
					<?php
					break;
				//Mental Note - What did I do with Stage 7?
				case 8:
					setup_log("Stage 8: Beginning Advanced Configuration");
					//Security Options
					?>
					<h2>Advanced Options 1</h2>
					<h3>Security Settings</h3>
					<p>Hey, below are the security settings for the site. You can leave these as the defaults I set, or change them however you like. Only touch these if you know what you're doing, and make sure you refer to the installation guide if in doubt.</p>
					<?php
					setup_log("Stage 8: Loading Input Form: Security");
					input_form("secure",9,true);
					?>
					<input type="button" class="ui-state-default ui-state-higlight" value="Cancel and Finish -&gt;" onclick="window.location = '?stage=-1';" />
					<?php
					break;
				case 9:
					//Advanced Logging Options
					//Process Received Data
					$data = array_keys($_POST);
					$config->falseify("secure"); //Fix for checkbox bools
					foreach ($data as $key) {
						if (is_bool($config->getNode('secure',$key))) {
							if ($_POST[$key] == "on") $config->setNode('secure',$key,true);
							else $config->setNode('secure',$key,false);
						} else {
							$config->setNode('secure',$key,$_POST[$key]);
						}
					}
					setup_log("Stage 9: Configuration Updated");
					?>
					<h2>Advanced Options 2</h2>
					<h3>Logging</h3>
					<p>By default I make sure that everything that happens is tracked. Below you can turn this off, and change the names of stored files. The MySQL Logfile is depreciated and will be removed shortly.</p>
					<?php
					setup_log("Stage 9: Loading Input Form: Logging");
					input_form("logs",10,true);
					?>
					<input type="button" class="ui-state-default ui-state-higlight" value="Cancel and Finish -&gt;" onclick="window.location = '?stage=-1';" />
					<div class="ui-state-highlight"><span class="ui-icon ui-icon-gear"></span>Hint: The log directory is in the paths and directories section.</div>
					<?php
					break;
				case 10:
					//Predefined Strings
					//Process Received Data
					$data = array_keys($_POST);
					$config->falseify("logs"); //Fix for checkbox bools
					foreach ($data as $key) {
						if (is_bool($config->getNode('logs',$key))) {
							if ($_POST[$key] == "on") $config->setNode('logs',$key,true);
							else $config->setNode('logs',$key,false);
						} else {
							$config->setNode('logs',$key,$_POST[$key]);
						}
					}
					setup_log("Stage 10: Configuration Updated");
					?>
					<h2>Advanced Options 3</h2>
					<h3>Predefined Text Strings</h3>
					<p>Here are all the various messages that the site can throw out. I've made them look as friendly as possible, but you can always tweak them. This section supports full HTML. Refer to the installation guide for information about formatting conventions.</p>
					<?php
					setup_log("Stage 10: Loading Input Form: Messages");
					input_form("messages",11,true);
					?>
					<input type="button" class="ui-state-default ui-state-higlight" value="Cancel and Finish -&gt;" onclick="window.location = '?stage=-1';" />
					<?php
					break;
				case 11:
					//Super Really Advanced
					//Process Received Data
					$data = array_keys($_POST);
					$config->falseify("messages"); //Fix for checkbox bools
					foreach ($data as $key) {
						if (is_bool($config->getNode('messages',$key))) {
							if ($_POST[$key] == "on") $config->setNode('messages',$key,true);
							else $config->setNode('messages',$key,false);
						} else {
							$config->setNode('messages',$key,$_POST[$key]);
						}
					}
					setup_log("Stage 11: Configuration Updated");
					?>
					<h2>Advanced Options 4</h2>
					<h3>Super Really Advanced</h3>
					<p>If you ignored my previous warnings about not changing things without consulting the Installation Guide, then you're very lucky to have such a great validation system like me. However, the following settings are really, really, important to me working, and changing a single one of them can cause the site to instantly collapse upon itself and subsequently explode, taking out a fair portion of the Internet with it. Please, please be carful.</p>
					<?php
					setup_log("Stage 11: Loading Input Form: Server");
					input_form("server",12,true);
					?>
					<input type="button" class="ui-state-default ui-state-higlight" value="Cancel and Finish -&gt;" onclick="window.location = '?stage=-1';" />
					<div class="ui-state-highlight"><span class="ui-icon ui-icon-gear"></span>Tip: There is a super secret additional section of the configuration. But it's so super secret it can't actually be accessed. It's used by the site to store temporary information that it needs. It automatically disappears at the end of a session, so don't even try to access it. Really.</div>
					<?php
					break;
				case 12:
					//End Advanced Settings
					//Process Received Data
					$data = array_keys($_POST);
					$config->falseify("server"); //Fix for checkbox bools
					foreach ($data as $key) {
						if (is_bool($config->getNode('server',$key))) {
							if ($_POST[$key] == "on") $config->setNode('server',$key,true);
							else $config->setNode('server',$key,false);
						} else {
							$config->setNode('server',$key,$_POST[$key]);
						}
					}
					setup_log("Stage 12: Configuration Updated");
					?>
					<h2>Advanced Options</h2>
					<h3>Finished</h3>
					<p>That's it. There are absolutely no more settings. You've customised the site to the max. Good for you. Click finish to actually save your changes, and boot into your new site. Don't forget to thank your favourite setup guide for all their help!</p>
					<input type="button" class="ui-state-default ui-state-higlight" value="Save and Finish -&gt;" onclick="window.location = '?stage=-1';" />
					<?php
					break;
				case -1:
					?>
					<p>Thanks for taking the time to set me up. I'm now saving the configuration to my offline directory. Once this page is loaded, you should be ready to go. Bye!</p>
					<div class="ui-state-highlight"><span class="ui-icon ui-icon-gear"></span>Note: Regardless of the directory chosen, {siteroot}/conf.txt must remain intact, or I'll forget where the offline directory is, and everything will break.</div>
					<?php
					echo nl2br(print_r($config,true));
					$path = $config->getNode('paths','offlineDir');
					$file = fopen($config->getNode('paths','path')."/conf.txt","w+");
					fwrite($file,$path."/conf.txt");
					fclose($file);
					$file = fopen($path."/conf.txt","w+");
					fwrite($file,serialize($config));
					fclose($file);
					debug_message("Data Saved.");
					setup_log("Finished: Configuration Stored");
					setup_log("Copying files to offline directory");
					$dirs = array("setup_files");
					while ($dir = array_pop($dirs)) {
						$handle = opendir(dirname(__FILE__)."/$dir");
						$subdir = str_replace("setup_files","",$dir);
						while ($file = readdir($handle)) {
							if ($file != "." and $file != "..") {//. and ..
								if (is_dir(dirname(__FILE__)."/$dir/$file")) {
									if (!is_dir($config->getNode('paths','offlineDir')."/$subdir/$file")) {
										if (!mkdir($config->getNode('paths','offlineDir')."/$subdir/$file")) {
											setup_log("Error: Couldn't create directory ".$config->getNode('paths','offlineDir')."/$subdir/$file");
										} else {
											setup_log("Created directory ".$config->getNode('paths','offlineDir')."/$subdir/$file");
										}
									}
									array_push($dirs,$dir."/$file");
								} else {
									if (!copy(dirname(__FILE__)."/$dir/$file",$config->getNode('paths','offlineDir')."/$subdir/$file")) {
										setup_log("Error: Failed to copy ".dirname(__FILE__)."/$dir/$file"." to ".$config->getNode('paths','offlineDir')."/$subdir/$file");
									} else {
										setup_log("Copied ".dirname(__FILE__)."/$dir/$file"." to ".$config->getNode('paths','offlineDir')."/$subdir/$file");
									}
								}
							}
						}
					}
					setup_log("Finished: Setup files copied");
					if (!unlink(dirname(__FILE__)."/../logs/temp_conf.txt")) {
						setup_log("Error: Could not delete temporary files");
					} else {
						setup_log("Finished: Temporary files deleted");
					}
					echo "<a href='../'>Go to the Admin CP!</a>";
					break;
				
				default:
					echo "<p>Oops! It looks like something went wrong loading this page. If you clicked a link, it is most likely that a server configuration error is involved. Regardless, I am unable to finish being configured using the current environment. Kthxbai.</p>";
					setup_log("Error: Stage $stage unknown");
			}
			if ($stage != -1) {
				if ($file = fopen(dirname(__FILE__)."/../logs/temp_conf.txt","w+")) {
					fwrite($file,serialize($config));
					fclose($file);
					setup_log("Stage $stage: Temporary Configuration data updated");
				} else {
					setup_log("Stage $stage: Error: Couldn't write temp_conf.txt");
				}
			}
		}
		?>
		<script type="text/javascript">$('#jquery-form').validate({errorClass: "ui-state-highlight"});</script>
        <?php
	}
	?>
</div>
</div>
<div id="dialog" class="ui-helper-hidden"></div>
<div id='footer'>I can't wait to be selling things all over the world!</div>
</body>
</html>