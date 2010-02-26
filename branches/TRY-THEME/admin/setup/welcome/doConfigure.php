<?php
require_once dirname(__FILE__)."/../header.inc.php";

//Generate the customised setup adventure! =D
switch ($_GET['mode']) {
	case "express":
		//Express Mode
		$_SESSION['mode'] = "express";
		$_SESSION['stage']['paths'] = true;
		$_SESSION['stage']['database'] = true;
		$_SESSION['stage']['about'] = true;
		break;
	case "complete":
		//Complete Mode
		$_SESSION['stage']['paths'] = true;
		$_SESSION['stage']['database'] = true;
		$_SESSION['stage']['about'] = true;
		$_SESSION['stage']['security'] = true;
		$_SESSION['stage']['shop'] = true;
		$_SESSION['stage']['orderstatus'] = true;
		$_SESSION['stage']['paypal'] = true;
		$_SESSION['stage']['messages'] = true;
		$_SESSION['stage']['pagination'] = true;
		$_SESSION['stage']['account'] = true;
		$_SESSION['stage']['smtp'] = true;
		$_SESSION['stage']['logs'] = true;
		$_SESSION['stage']['server'] = true;
		$_SESSION['stage']['tabs'] = true;
		$_SESSION['stage']['widget_carousel'] = true;
		$_SESSION['stage']['viewItem'] = true;
		break;
	case "tailored":
		//Tailored Mode
		$_SESSION['stage']['paths'] = true;
		$_SESSION['stage']['database'] = true;
		$_SESSION['stage']['about'] = true;
		$_SESSION['stage']['paypal'] = isset($_GET['paypal']);
		break;
	case "custom":
		//Custom Mode
		$_SESSION['stage']['paths'] = true;
		$_SESSION['stage']['database'] = true;
		$_SESSION['stage']['about'] = true;
		$_SESSION['stage']['security'] = isset($_POST['security']);
		$_SESSION['stage']['shop'] = isset($_POST['shop']);
		$_SESSION['stage']['orderstatus'] = isset($_POST['orderstatus']);
		$_SESSION['stage']['paypal'] = isset($_POST['paypal']);
		$_SESSION['stage']['messages'] = isset($_POST['messages']);
		$_SESSION['stage']['pagination'] = isset($_POST['pagination']);
		$_SESSION['stage']['account'] = isset($_POST['account']);
		$_SESSION['stage']['smtp'] = isset($_POST['smtp']);
		$_SESSION['stage']['logs'] = isset($_POST['logs']);
		$_SESSION['stage']['server'] = isset($_POST['server']);
		$_SESSION['stage']['tabs'] = isset($_POST['tabs']);
		$_SESSION['stage']['widget_carousel'] = isset($_POST['widget_carousel']);
		$_SESSION['stage']['viewItem'] = isset($_POST['viewItem']);
		break;
}

//Build sample conf
$_SESSION['config'] = new Config("Default Configuration",$INIT_DEBUG);

//Main Tree
$_SESSION['config']->addTree("site", "Main Settings");

$_SESSION['config']->setNode("site", "enabled", true, "Enable Site");
$_SESSION['config']->setNode("site", "password", md5("123456"), "Admin Password");
$_SESSION['config']->setNode("site", "vat", 17.5, "VAT Rate");
$_SESSION['config']->setNode("site", "country", "GB", "Default Country");
$_SESSION['config']->setNode("site", "shopMode", true, "Shop Mode");
$_SESSION['config']->setNode("site", "homeTab", true, "Home Tab");
$_SESSION['config']->setNode("site", "sendFeedback", true, "Send Feedback");
$_SESSION['config']->setNode("site", "version", "0.9.232", "Version");
$_SESSION['config']->setNode("site", "loginTab", true, "Login Tab");

//Paths and Directories Tree
$_SESSION['config']->addTree("paths", "Site Paths and Directories");

$_SESSION['config']->setNode("paths", "root", "http://".$_SERVER['HTTP_HOST'].preg_replace('/\/admin\/setup\/.*$/i','',$_SERVER['REQUEST_URI']), "Home URL");
$_SESSION['config']->setNode("paths", "secureRoot", "https://".$_SERVER['HTTP_HOST'].preg_replace('/\/admin\/setup\/.*$/i','',$_SERVER['REQUEST_URI']), "Secure Home URL");
$_SESSION['config']->setNode("paths", "path", preg_replace("/(\\\\|\/)admin(\\\\|\/)setup(.*)/i","",dirname(__FILE__)), "Home Directory");
$_SESSION['config']->setNode("paths", "offlineDir", "C:/inetpub/data", "Offline Directory");
$_SESSION['config']->setNode("paths", "logDir", $_SESSION['config']->getNode("paths", "offlineDir")."/logs", "Server Log Directory");

//Secure Site
$_SESSION['config']->addTree("secure", "Secure Site Settings");

$_SESSION['config']->setNode("secure", "enabled", true, "Enable Secure Transaction Processing");
$_SESSION['config']->setNode("secure", "admin", true, "Force Secure Admin CP");

//Database
$_SESSION['config']->addTree("database", "Database Server Settings");

$_SESSION['config']->setNode("database", "type", "mysql", "Database Engine");
$_SESSION['config']->setNode("database", "address", "localhost", "Server Address");
$_SESSION['config']->setNode("database", "port", "3306", "Server Port");
$_SESSION['config']->setNode("database", "uname", "", "Username");
$_SESSION['config']->setNode("database", "password", "", "Password");
$_SESSION['config']->setNode("database", "name", "flumpshop", "Main Site Database Name");

//Advanced Server
$_SESSION['config']->addTree("server", "Advanced Server Configuration");

$_SESSION['config']->setNode("server", "rewrite", false, "Enable URL Rewrite");
$_SESSION['config']->setNode("server", "holdTimeout", 60, "Item Hold Timeout");
$_SESSION['config']->setNode("server", "commitPayments", true, "Commit Payments (Debug)");
$_SESSION['config']->setNode("server", "crawlerAgents", "Googlebot|msnbot|Slurp", "Web Crawler Useragents");
$_SESSION['config']->setNode("server", "debug", false, "Enable Debug Mode");
$_SESSION['config']->setNode("server", "cronFreq", 30, "Cron Frequency (mins)");
$_SESSION['config']->setNode("server", "backupFreq", 24, "Backup Frequency (hrs)");
$_SESSION['config']->setNode("server", "analyticsID", "Google Analytics ID");

//Messages
$_SESSION['config']->addTree("messages", "Predefined Text Strings");

$_SESSION['config']->setNode("messages", "footer", "Designed and built by Flumpnet", "Page Footer");
$_SESSION['config']->setNode("messages", "adminDenied", "You must enter the administrator password in the Site Admin section before you can perform this action.", "Admin Access Denied");
$_SESSION['config']->setNode("messages", "crawler", "A Crawler User Agent has been detected. Some site features are disabled to reduce server load.", "Crawler Agent");
$_SESSION['config']->setNode("messages", "maintenance", "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>We've shut down the site temporarily for scheduled maintenance. It should be back online in a few moments.</div>", "Site Disabled");
$_SESSION['config']->setNode("messages", "name", "Flumpshop", "Site Name");
$_SESSION['config']->setNode("messages", "tagline", "Beta!", "Tagline");
$_SESSION['config']->setNode("messages", "keywords", "Flumpshop online shop buy ecommerce sales", "Keywords");
$_SESSION['config']->setNode("messages", "defaultCategoryName", "Uncategorised", "Default Category Name");
$_SESSION['config']->setNode("messages", "defaultCategoryDesc", "Details for this category are unavailable.", "Default Category Description");
$_SESSION['config']->setNode("messages", "basketRemItemConf", "Are you sure you want to remove this item from your basket?", "Remove from Basket");
$_SESSION['config']->setNode("messages", "basketEmptyConf", "Are you sure you want to empty your basket?", "Empty Basket");
$_SESSION['config']->setNode("messages", "noScript", "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Please enable JavaScript for this page to function properly.</div>", "JavaScript Disabled");
$_SESSION['config']->setNode("messages", "insufficientStock", "There is insufficient stock for this item. Please reduce the size of your order or try again later.", "Insufficient Stock");
$_SESSION['config']->setNode("messages", "featuredItemHeader", "Featured Item", "Featured Item Header");
$_SESSION['config']->setNode("messages", "popularItemHeader", "Most Popular", "Popular Item Header");
$_SESSION['config']->setNode("messages", "latestNewsHeader", "Latest News", "Latest News Header");
$_SESSION['config']->setNode("messages", "technicalHeader", "Technical Tips", "Technical Help Header");

//Category Messages
$_SESSION['config']->setNode("messages", "subcatHeader", "This section has these subcategories", "Subcategory Header");

//Payment Messages
$_SESSION['config']->setNode("messages", "transactionCancelled", "<h1>Transaction Cancelled</h1><p>You cancelled the purchase before it was completed.</p>","Transaction Cancelled");
$_SESSION['config']->setNode("messages", "transactionFailed", "The payment server reported that the transaction did not complete succesfully. Please try again later.","Transaction Failed");
$_SESSION['config']->setNode("messages", "paymentComplete", "Your payment is being processed by PayPal and you will receive e-mail confirmation shortly. Your order has now been stored in our database and you will receive an additional e-mail once the item(s) have been dispatched.","Payment Complete");

//AJAX Errors
$_SESSION['config']->setNode("messages", "ajax500", "An 500 Internal Server error occured when trying to load a remote endpoint.", "AJAX 500 Error");
$_SESSION['config']->setNode("messages", "ajax404", "A remote endpoint was not found.", "AJAX 404 Error");
$_SESSION['config']->setNode("messages", "ajaxError", "An unknown error was encountered loading a remote endpoint.", "AJAX Error");

//Pagination Messages
$_SESSION['config']->setNode("messages", "firstPage", "&lt;&lt;", "First Page Link");
$_SESSION['config']->setNode("messages", "lastPage", "&gt;&gt;", "Last Page Link");
$_SESSION['config']->setNode("messages", "previousPage", "&lt;", "Previous Page Link");
$_SESSION['config']->setNode("messages", "nextPage", "&gt;", "Next Page Link");

//User Message
$_SESSION['config']->setNode("messages", "userNoCustomer", "You haven't given me any contact information yet. Do you want to do this now?", "No Contact Details");
$_SESSION['config']->setNode("messages", "loginNeeded", "You must be logged in to perform that action.", "Login Required");
$_SESSION['config']->setNode("messages", "countryNotSupported", "Sorry, but we can't deliver to your country online. We're working hard to expand our range, but you must call or email us to arrange delivery to this region.", "Unsupported Country");

//Legal Additions
$_SESSION['config']->setNode("messages", "email", "sales@".$_SERVER['HTTP_HOST'], "Email Address");
$_SESSION['config']->setNode("messages", "address", "[Please enter your business address here]", "Address");

//Full Page Content
$_SESSION['config']->setNode("messages", "404", 
<<<EOT
<h1>Not Being Seen</h1>
<p>I'm really sorry. I've looked high and low, left and right, but the page you've asked me to magic upon your screen just won't happen. Make sure you've asked me to look in the right place, and if you clicked a button <a href="../reportbug.php">click this one</a> too, and tell me what didn't work.</p>
<p>If all else fails, then you can assume that the page is currently on holiday, or is getting very good at not being seen.</p>
EOT
, "404 Error Page");
$_SESSION['config']->setNode("messages", "homePage", 
<<<EOT
Hello, it's me again, the Flumpnet robot. I thought you might like to know this:

This is another one of my magical placeholder messages. This means that as soon as you tell me to put something here, then I'll make sure you never see this thing again. To do this, simply login to the Administrator Control Panel, and click Home Page in the Pages section.
It sounds great, right? That all you have to do is click a few buttons instead of spending hours digging through the thousands and thousands of lines of code the make this website, and more importantly, me, happen. Happy Interneting!
EOT
, "Home Page");
$_SESSION['config']->setNode("messages", "privacyPolicy",
<<<EOT
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
<p>A cookie is a small file which asks permission to be placed on your device hard drive. Once you agree, the file is added and the cookie helps analyse web traffic or lets you know when you visit a particular site. Cookies allow web applications to respond to you as an individual. The web application can tailor its operations to your needs, likes and dislikes by gathering and remembering information about your preferences.</p> 

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
$_SESSION['config']->setNode("messages", "disclaimer",
<<<EOT
<p>The information contained in this website is for general information purposes only. The information is provided by [[name]] and while we endeavour to keep the information up to date and correct, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly at your own risk.</p>

<p>In no event will we be liable for any loss or damage including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever arising from loss of data or profits arising out of, or in connection with, the use of this website.</p>

<p>Through this website you are able to link to other websites which are not under the control of [[name]]. We have no control over the nature, content and availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.</p>

<p>Every effort is made to keep the website up and running smoothly. However, [[name]] takes no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond our control.</p>
EOT
,"Disclaimer");
$_SESSION['config']->setNode("messages", "termsConditions",
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

$_SESSION['config']->setNode("messages", "aboutPage",
<<<EOT
<p>Hello, it's me again, the Flumpnet robot. It looks like this is another one of those placeholder messages. You'll have to head over to the Admin CP and set your own content for this page under Pages->About Page for this messages to go away.</p>
EOT
,"About Page");

$_SESSION['config']->setNode("messages", "contactPage",
<<<EOT
<p>Hello, it's me again, the Flumpnet robot. It looks like this is another one of those placeholder messages. You'll have to head over to the Admin CP and set your own content for this page under Pages->Contact Page for this messages to go away.</p>
EOT
,"Contact Page");

//Navigation Advert
$_SESSION['config']->setNode("messages", "navAdvert", "Add custom text here in the Admin CP", "Navigation Bar Text");

//Form Messages
$_SESSION['config']->setNode("messages", "formFieldRequired", "This field is required.", "Required Field");

//Log Files
$_SESSION['config']->addTree("logs", "Server Log Files");

$_SESSION['config']->setNode("logs", "enabled", true, "Enable Server Logs");
$_SESSION['config']->setNode("logs", "errors", "errors.log", "Error Logfile");

//Order Statuses
$_SESSION['config']->addTree("orderstatus", "Order Statuses");

$_SESSION['config']->setNode("orderstatus", "0", array("name" => "New", "active" => true), "Status ID #0");
$_SESSION['config']->setNode("orderstatus", "1", array("name" => "Waiting for Stock", "active" => true), "Status ID #1");
$_SESSION['config']->setNode("orderstatus", "2", array("name" => "Payment Rejected", "active" => false), "Status ID #2");
$_SESSION['config']->setNode("orderstatus", "3", array("name" => "Dispatched", "active" => false), "Status ID #3");
$_SESSION['config']->setNode("orderstatus", "4", array("name" => "Returning", "active" => true), "Status ID #4");
$_SESSION['config']->setNode("orderstatus", "5", array("name" => "Returned", "active" => false), "Status ID #5");
$_SESSION['config']->setNode("orderstatus", "6", array("name" => "Acknowledged", "active" => true), "Status ID #6");

//PayPal
$_SESSION['config']->addTree("paypal","PayPal Settings");

$_SESSION['config']->setNode("paypal", "enabled", false, "Enable PayPal");
$_SESSION['config']->setNode("paypal", "uname", "", "PayPal API Username");
$_SESSION['config']->setNode("paypal", "pass", "", "PayPal API Password");
$_SESSION['config']->setNode("paypal", "apiKey", "", "PayPal API Key");

//Pagination Options
$_SESSION['config']->addTree("pagination", "Pagination Options");

$_SESSION['config']->setNode("pagination", "sitemapPerPage", 10, "Per Page: Sitemap");
$_SESSION['config']->setNode("pagination", "categoryPerPage", 10, "Per Page: Category View");
$_SESSION['config']->setNode("pagination", "searchPerPage", 25, "Per Page: Search Results");
$_SESSION['config']->setNode("pagination", "editItemsPerPage", 25, "Per Page: Edit Items List");

//Account Settings
$_SESSION['config']->addTree("account", "Account Settings");

$_SESSION['config']->setNode("account", "requireAllAtSignup", false, "Require All Details at Signup");
$_SESSION['config']->setNode("account", "requireEmailValidation", false, "Require Email Address Confirmation");
$_SESSION['config']->setNode("account", "validationTimeout", 48, "Email Validation Timeout (hrs)");

//Mail Settings
$_SESSION['config']->addTree("smtp", "Mail Server Settings");

$_SESSION['config']->setNode("smtp", "host", "localhost", "SMTP Server");
$_SESSION['config']->setNode("smtp", "port", 25, "SMTP Port");
$_SESSION['config']->setNode("smtp", "uname", "daemon", "Username");
$_SESSION['config']->setNode("smtp", "password", "", "Password");
$_SESSION['config']->setNode("smtp", "email", "daemon@".preg_replace("/^www\./i","",$_SERVER['HTTP_HOST']), "Email Address");

//Carousel Widget
$_SESSION['config']->addTree("widget_carousel", "Carousel Widget Settings");

$_SESSION['config']->setNode("widget_carousel", "onIndex", true, "Show on Home Page");
$_SESSION['config']->setNode("widget_carousel", "indexPosition", "right", "Position on Home Page");
$_SESSION['config']->setNode("widget_carousel", "indexHeight", 800, "Height on Home Page");
$_SESSION['config']->setNode("widget_carousel", "images", 10, "Number of Images");
$_SESSION['config']->setNode("widget_carousel", "imageScale", 1, "Image Scale");

//Item View Options
$_SESSION['config']->addTree("viewItem", "Item View Settings");

$_SESSION['config']->setNode("viewItem", "showID", false, "Show Item ID");
$_SESSION['config']->setNode("viewItem", "imageScale", 1, "Image Scale");
$_SESSION['config']->setNode("viewItem", "catCols", 4, "Category Columns");
$_SESSION['config']->setNode("viewItem", "catTextPos", "bottom", "Category Text Position");
$_SESSION['config']->setNode("viewItem", "catChars", 30, "Category Item Summary Length");
$_SESSION['config']->setNode("viewItem", "homeTextPos", "bottom", "Home Text Position");
$_SESSION['config']->setNode("viewItem", "homeChars", 30, "Home Item Summary Length");


//Save
$_SESSION['config'] = serialize($_SESSION['config']);
?><script type="text/javascript">parent.leftFrame.window.location = '../index.php?frame=leftFrame&p=1.3';</script>
<h1>Ready To Go</h1>
<p>Right, I've remembered all of that. Now, lets begin with the actual setting up!</p>
<button onclick='window.location = "../stages/paths.php"; parent.leftFrame.window.location = "../index.php?frame=leftFrame&p=2.1";'>Begin</button>
<script type="text/javascript">$('button').button(); window.location = "../stages/paths.php"; parent.leftFrame.window.location = "../index.php?frame=leftFrame&p=2.1";</script>