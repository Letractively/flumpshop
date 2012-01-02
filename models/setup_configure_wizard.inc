<?php
/**
 *  This model takes the input from the Customise experience form and configures
 *  the setup wizard itself. It also generates the default configuration, then
 *  sets the value of $setup_next_url as the page the client should visit next.
 *
 *  This file is part of Flumpshop.
 *
 *  Flumpshop is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Flumpshop is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with Flumpshop.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 *  @Name models/setup_configure_stages.inc
 *  @Version 1.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */
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
    $_SESSION['stage']['homePage'] = true;
    $_SESSION['stage']['viewItem'] = true;
    $_SESSION['stage']['delivery'] = true;
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
    $_SESSION['stage']['homePage'] = isset($_POST['homePage']);
    $_SESSION['stage']['viewItem'] = isset($_POST['viewItem']);
    $_SESSION['stage']['delivery'] = isset($_POST['delivery']);
    break;
}

//Build sample conf
$_SESSION['config'] = new Config("Default Configuration", false);

//Main Tree
$_SESSION['config']->addTree("site", "Main Settings",
        'This section contains the most important settings for the site, including the second-tier password and the theme.');

$_SESSION['config']->setNode("site", "enabled", true, "Enable Site", 'When this option is disabled, any visitors to the site are provided with a "Down for Maintenance" page. You can still access the Admin CP. This is normally changed automatically by the system during upgrades.');
$_SESSION['config']->setNode("site", "password", sha1("123456"), "Admin Password", 'This is the encrypted second tier password. Unless you know what you\'re doing. Don\'t touch this or you could lock yourself out.');
$_SESSION['config']->setNode("site", "vat", 20, "VAT Rate", 'This is the default Value Added Tax rate that is applied to orders, as a percentage.');
$_SESSION['config']->setNode("site", "country", "GB", "Default Country", 'This is the two-letter country code of the default country this site operated in.');
$_SESSION['config']->setNode("site", "shopMode", true, "Shop Mode", 'When enabled, visitors can see stock levels, add items to a basket, then send them to a checkout.');
$_SESSION['config']->setNode("site", "homeTab", true, "Home Tab", 'When enabled, the primary navigation bar has a "Home" link on it.');
$_SESSION['config']->setNode("site", "sendFeedback", true, "Send Feedback", 'When enabled, a "Send Feedback" link is displayed on every page (depending on theme) that allows users to fill in a contact form.');
$_SESSION['config']->setNode("site", "loginTab", true, "Login Tab", 'When enabled, the primary navigation bar has a "Login" link on it. Disabling this essentially disables access to the user authentication part of the site');
$_SESSION['config']->setNode("site", "theme", "flumpshop", "Theme", 'This is the theme that is used on the site frontend.');
$_SESSION['config']->setNode("site", "templateMode", "core", "Template Mode", 'Unless specified by the theme, leave this as "core"');
$_SESSION['config']->setNode('site', 'debug', false, 'Debug Mode' , 'If this is enabled, you\'ll see a lot more information produced on every page detailing what is or isn\'t going on. For developer use only.');

//Paths and Directories Tree
$_SESSION['config']->addTree("paths", "Site Paths and Directories", 'This Configuration tree is used to store URLs and file paths that are important to Flumpshop\'s operation.');

$_SESSION['config']->setNode("paths", "root", "http://" . $_SERVER['HTTP_HOST'] . preg_replace('/\/admin\/setup\/.*$/i', '', $_SERVER['REQUEST_URI']), "Home URL", 'This is the URL of the main directory of the site. This should be automatically worked out, but check it just to be sure. Change this if you move the Flumpshop installation to another website later.');
$_SESSION['config']->setNode("paths", "secureRoot", "https://" . $_SERVER['HTTP_HOST'] . preg_replace('/\/admin\/setup\/.*$/i', '', $_SERVER['REQUEST_URI']), "Secure Home URL", 'This is the URL of the main directory of the site when using SSL encryption. This should be automatically worked out, but check it just to be sure. Change this if you move the Flumpshop installation to another website later, or if you use a different domain for secure pages, e.g. secure.flump.me');
$_SESSION['config']->setNode("paths", "path", preg_replace("/(\\\\|\/)models(.*)/i", "", dirname(__FILE__)), "Home Directory",'This is the physical location of the site on the server. This should be automatically worked out, but check it just to be sure. Change this is you ever move the server Flumpshop is hosted on.');
$_SESSION['config']->setNode("paths", "offlineDir", "C:/inetpub/data", "Offline Directory", 'DEPRECATED. This is the physical location of a directory on the server that Flumpshop has full permissions over. It is highly recommeneded that you ensure this folder cannot be accessed from the Internet. Make sure this directory already exists, as I won\'t create it for you.');
$_SESSION['config']->setNode("paths", "logDir", $_SESSION['config']->getNode("paths", "offlineDir") . "/logs", "Server Log Directory", 'This is the physical location of a directory that Flumpshop has full permissions over. By default, this is a subdirectory called logs within the Offline Direcory. Make sure this directory already exists, as I won\'t create it for you.');

//Secure Site
$_SESSION['config']->addTree("secure", "Secure Site Settings");

$_SESSION['config']->setNode("secure", "enabled", true, "Enable Secure Transaction Processing", 'Whether the server should redirect to the HTTPS site for transaction processing.');
$_SESSION['config']->setNode("secure", "admin", true, "Force Secure Admin CP", 'Whether the server should force the Admin CP to be accessed using HTTPS.');

//Database
$_SESSION['config']->addTree("database", "Database Server Settings", 'These settings control how Flumpshop connects to the backend database server. Avoid changing, unless you know what you are doing.');

$_SESSION['config']->setNode("database", "type", "mysql", "Database Engine", 'Flumpshop currently only supports the MySQL database type.');
$_SESSION['config']->setNode("database", "address", "localhost", "Server Address", 'This is the hostname or IP address of the server the MySQL Service is running on. Quite often, this will be localhost, the same server running the web frontend, although this may be different in larger environments.');
$_SESSION['config']->setNode("database", "port", "3306", "Server Port", 'The port used to connect to the MySQL Server. Usually, this is 3306.');
$_SESSION['config']->setNode("database", "uname", "", "Username", 'This is the username of the MySQL Account Flumpshop will use to connect to the server. It will need full control over the database, and must be created in MySQL before Flumpshop can use it.');
$_SESSION['config']->setNode("database", "password", "", "Password", 'This is the password associated with the above account.');
$_SESSION['config']->setNode("database", "name", "flumpshop", "Main Site Database Name", 'This is the name of the database that Flumpshop will use. If it doesn\'t exit, the Flumpnet Robot will try to create it for you if the account has the necessary permissions. If you have multiple Flumpshop installs on the same MySQL Server, they will each need a different database name.');

//Advanced Server
$_SESSION['config']->addTree("server", "Advanced Server Configuration");

$_SESSION['config']->setNode("server", "rewrite", false, "Enable URL Rewrite");
$_SESSION['config']->setNode("server", "holdTimeout", 60, "Item Hold Timeout");
$_SESSION['config']->setNode("server", "commitPayments", true, "Commit Payments (Debug)");
$_SESSION['config']->setNode("server", "crawlerAgents", "Googlebot|msnbot|Slurp", "Web Crawler Useragents");
$_SESSION['config']->setNode("server", "debug", false, "Enable Debug Mode");
$_SESSION['config']->setNode("server", "cronFreq", 30, "Cron Frequency (mins)");
$_SESSION['config']->setNode("server", "backupFreq", 24, "Backup Frequency (hrs)");
$_SESSION['config']->setNode("server", "analyticsID", "", "Google Analytics ID");

//Messages
$_SESSION['config']->addTree("messages", "Predefined Text Strings");

//Global Messages
$_SESSION['config']->setNode("messages", "footer", "Designed and built by Flumpnet", "Page Footer");
$_SESSION['config']->setNode("messages", "crawler", "A Crawler User Agent has been detected. Some site features are disabled to reduce server load.", "Crawler Agent");
$_SESSION['config']->setNode("messages", "name", "Flumpshop", "Site Name", 'The name of this website, which is used in legal pages, and the header.');
$_SESSION['config']->setNode("messages", "tagline", "Make it happen", "Tagline", 'The tagline that appears below the Site Name.');
$_SESSION['config']->setNode("messages", "keywords", "Flumpshop online shop buy ecommerce sales", "Keywords");
$_SESSION['config']->setNode("messages", "defaultCategoryName", "Uncategorised", "Default Category Name");
$_SESSION['config']->setNode("messages", "defaultCategoryDesc", "Details for this category are unavailable.", "Default Category Description");
$_SESSION['config']->setNode("messages", "navAdvert", "Add custom text here in the Admin CP", "Navigation Bar Text");
$_SESSION['config']->setNode("messages", "featuresName", "Features", "Item Features Name");
$_SESSION['config']->setNode("messages", "subcatHeader", "Sections", "Subcategories Header");
$_SESSION['config']->setNode("messages", "loading", "Loading...", "Loading Message");

//Index Page
$_SESSION['config']->setNode("messages", "featuredItemHeader", "Featured Item", "Featured Item Header");
$_SESSION['config']->setNode("messages", "popularItemHeader", "Most Popular", "Popular Item Header");
$_SESSION['config']->setNode("messages", "latestNewsHeader", "Latest News", "Latest News Header");
$_SESSION['config']->setNode("messages", "technicalHeader", "Technical Tips", "Technical Help Header");

//Error Messages
$_SESSION['config']->setNode("messages", "ajax500", "An 500 Internal Server error occured when trying to load a remote endpoint.", "AJAX 500 Error");
$_SESSION['config']->setNode("messages", "ajax404", "A remote endpoint was not found.", "AJAX 404 Error");
$_SESSION['config']->setNode("messages", "ajax403", "The Flumpshop Web Service is not available. Please contact the system administrator.", "AJAX 403 Error");
$_SESSION['config']->setNode("messages", "ajaxError", "An unknown error was encountered loading a remote endpoint.", "AJAX Error");
$_SESSION['config']->setNode("messages", "adminDenied", "You do not have permission to perform that action.", "Admin Access Denied");
$_SESSION['config']->setNode("messages", "maintenance", "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>We've shut down the site temporarily for scheduled maintenance. It should be back online in a few moments.</div>", "Site Disabled");
$_SESSION['config']->setNode("messages", "transactionFailed", "The payment server reported that the transaction did not complete succesfully. Please try again later.", "Transaction Failed");
$_SESSION['config']->setNode("messages", "transactionCancelled", "<h1>Transaction Cancelled</h1><p>You cancelled the purchase before it was completed.</p>", "Transaction Cancelled");
$_SESSION['config']->setNode("messages", "noScript", "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Please enable JavaScript for this page to function properly.</div>", "JavaScript Disabled");
$_SESSION['config']->setNode("messages", "insufficientStock", "There is insufficient stock for this item. Please reduce the size of your order or try again later.", "Insufficient Stock");
$_SESSION['config']->setNode("messages", "formFieldRequired", "This field is required.", "Required Field");

//Confirmation Messages
$_SESSION['config']->setNode("messages", "paymentComplete", "Your payment is being processed by PayPal and you will receive e-mail confirmation shortly. Your order has now been stored in our database and you will receive an additional e-mail once the item(s) have been dispatched.", "Payment Complete");

//Questions
$_SESSION['config']->setNode("messages", "basketRemItemConf", "Are you sure you want to remove this item from your basket?", "Remove from Basket");
$_SESSION['config']->setNode("messages", "basketEmptyConf", "Are you sure you want to empty your basket?", "Empty Basket");

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
$_SESSION['config']->setNode("messages", "email", "sales@" . $_SERVER['HTTP_HOST'], "Email Address", 'The email address anyone can use, anytime, to contact a person who maintains or is in some way connected to the site. Your site, not the Flumpshop system in general. We covered all bases there. HA!');
$_SESSION['config']->setNode("messages", "address", "[Please enter your business address here]", "Address", 'The address of your business, or the registered office of your company.');

//Page Titles
$_SESSION['config']->setNode("messages", "titlesIndexPrefix", "Welcome to ", "Home Page Title Prefix");
$_SESSION['config']->setNode("messages", "contactPageHeader", "Contact Us", "Contact Us Page Header");
$_SESSION['config']->setNode("messages", "aboutPageHeader", "About Us", "About Us Page Header");
$_SESSION['config']->setNode("messages", "sendFeedbackHeader", "Send Feedback", "Send Feedback Page Header");
$_SESSION['config']->setNode("messages", "privacyPolicyHeader", "Privacy Policy", "Privacy Policy Page Header");
$_SESSION['config']->setNode("messages", "termsConditionsHeader", "Terms and Conditions", "Terms and Conditions Page Header");
$_SESSION['config']->setNode("messages", "disclaimerHeader", "Disclaimer", "Disclaimer Page Header");
$_SESSION['config']->setNode("messages", "searchHeader", "Search", "Search Page Header");
$_SESSION['config']->setNode("messages", "checkoutHeader", "Checkout", "Checkout Header");
$_SESSION['config']->setNode("messages", "basketHeader", "Basket", "Basket Header");
$_SESSION['config']->setNode("messages", "maintenanceHeader", "Unavailable", "Maintenance Header");

//Content Placeholders
$_SESSION['config']->setNode("messages", "featuredItemsPlaceholder", "You can tell the Flumpnet Robot to show two items in this section, by accessing the Admin CP, then clicking Products->Featured Items.", "Featured Items Placeholder");
$_SESSION['config']->setNode("messages", "popularItemsPlaceholder", "Sorry, our site has yet to gather sufficient information to show you here yet. Please come back later to see what everyone else is looking at!", "Popular Items Placeholder");
$_SESSION['config']->setNode("messages", "noNewsPlaceholder", "No items of the specified type have ever been posted here.", "No News Placeholder");

//Item Page
$_SESSION['config']->setNode("messages", "itemDelivery", "Delivery: ", "Item Delivery Prefix");
$_SESSION['config']->setNode("messages", "itemDeliveryUnavail", "Cannot deliver to your area online", "Item Delivery Unavailable");

//Checkout Messages
$_SESSION['config']->setNode("messages", "basketGoBackLink", "<-- Go Back", "Basket Go Back Link");
$_SESSION['config']->setNode("messages", "basketLinkToCheckout", "Go to checkout", "Basket Checkout Link");
$_SESSION['config']->setNode("messages", "basketEmpty", "Your basket is empty.", "Basket Empty");
$_SESSION['config']->setNode("messages", "paymentMethodHeader", "Choose a Payment Method", "Payment Method Title");

//Full Page Content
$_SESSION['config']->setNode("messages", "404", <<<EOT
<h1>Not Being Seen</h1>
<p>I'm really sorry. I've looked high and low, left and right, but the page you've asked me to magic upon your screen just won't happen. Make sure you've asked me to look in the right place, and if you clicked a button <a href="../reportbug.php">click this one</a> too, and tell me what didn't work.</p>
<p>If all else fails, then you can assume that the page is currently on holiday, or is getting very good at not being seen.</p>
EOT
        , "404 Error Page");
$_SESSION['config']->setNode("messages", "homePage", <<<EOT
Hello, it's me again, the Flumpnet robot. I thought you might like to know this:

This is another one of my magical placeholder messages. This means that as soon as you tell me to put something here, then I'll make sure you never see this thing again. To do this, simply login to the Administrator Control Panel, and click Home Page in the Pages section.
It sounds great, right? That all you have to do is click a few buttons instead of spending hours digging through the thousands and thousands of lines of code the make this website, and more importantly, me, happen. Happy Interneting!
EOT
        , "Home Page");
$_SESSION['config']->setNode("messages", "privacyPolicy", <<<EOT
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
        , "Privacy Policy");
$_SESSION['config']->setNode("messages", "disclaimer", <<<EOT
<p>The information contained in this website is for general information purposes only. The information is provided by [[name]] and while we endeavour to keep the information up to date and correct, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly at your own risk.</p>

<p>In no event will we be liable for any loss or damage including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever arising from loss of data or profits arising out of, or in connection with, the use of this website.</p>

<p>Through this website you are able to link to other websites which are not under the control of [[name]]. We have no control over the nature, content and availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.</p>

<p>Every effort is made to keep the website up and running smoothly. However, [[name]] takes no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond our control.</p>
EOT
        , "Disclaimer");
$_SESSION['config']->setNode("messages", "termsConditions", <<<EOT
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

$_SESSION['config']->setNode("messages", "aboutPage", <<<EOT
<p>Hello, it's me again, the Flumpnet robot. It looks like this is another one of those placeholder messages. You'll have to head over to the Admin CP and set your own content for this page under Pages->About Page for this messages to go away.</p>
EOT
        , "About Page");

$_SESSION['config']->setNode("messages", "contactPage", <<<EOT
<p>Hello, it's me again, the Flumpnet robot. It looks like this is another one of those placeholder messages. You'll have to head over to the Admin CP and set your own content for this page under Pages->Contact Page for this messages to go away.</p>
EOT
        , "Contact Page");

//Verification Failure
$_SESSION['config']->setNode("messages", "accountVerifyFail", <<<EOT
<h1 class="content">Verification Failed</h1>
<p>This may be because you have already activated account, or you took too long to verify your email address and your account was deleted, and you will have to register again.</p>
EOT
        , "Account Verification Failure");

//Verification Success
$_SESSION['config']->setNode("messages", "accountVerifySuccess", <<<EOT
<h1 class="content">Verification Successful</h1>
<p>Your email address has now been verified, and your account activated. You can now log in to our website using the button above.</p>
EOT
        , "Account Verification Failure");

//Emails
$_SESSION['config']->setNode("messages", "confirmAccountEmail", <<<EOT
<html><head><title>Registration Confirmation</title></head><body>
Hello, and thanks for registering for an account on the [[name]] website.<br /><br />
In order to complete the signup process, I need you to confirm that you own this email address. To do so, simply click the link below, or copy and paste it into your web browser.<br />
__regLink__<br />
If you do not confirm your email address within 1 hours, you will have to sign up again. If you did not sign up, please disregard this email.
</body></html>
EOT
        , "Email Address Confirmation Email");

//Log Files
$_SESSION['config']->addTree("logs", "Server Log Files");

$_SESSION['config']->setNode("logs", "enabled", true, "Enable Server Logs");
$_SESSION['config']->setNode("logs", "errors", "errors.log", "Error Logfile");
$_SESSION['config']->setNode('logs', 'xmldblogs', $_INIT_DEBUG, 'Detailed Database Logs', 'Requires the SimpleXML extension. When enabled, every query executed against the database is logged in XML files on a per-session basis.');

//Order Statuses
$_SESSION['config']->addTree("orderstatus", "Order Statuses");

$_SESSION['config']->setNode('orderstatus', '0', array('name' => 'Proforma Invoice', 'actuve' => false), 'Status ID #0');
$_SESSION['config']->setNode("orderstatus", '1', array('name' => 'New', "active" => true), "Status ID #1");
$_SESSION['config']->setNode("orderstatus", "2", array("name" => "Waiting for Stock", "active" => true), "Status ID #2");
$_SESSION['config']->setNode("orderstatus", "3", array("name" => "Payment Rejected", "active" => false), "Status ID #3");
$_SESSION['config']->setNode("orderstatus", "4", array("name" => "Dispatched", "active" => false), "Status ID #4");
$_SESSION['config']->setNode("orderstatus", "5", array("name" => "Returning", "active" => true), "Status ID #5");
$_SESSION['config']->setNode("orderstatus", "6", array("name" => "Returned", "active" => false), "Status ID #6");
$_SESSION['config']->setNode("orderstatus", "7", array("name" => "Acknowledged", "active" => true), "Status ID #7");

//PayPal
$_SESSION['config']->addTree("paypal", "PayPal Settings");

$_SESSION['config']->setNode("paypal", "enabled", false, "Enable PayPal", 'When checked, Flumpshop will offer PayPal as a form of payment for your customers.');
$_SESSION['config']->setNode("paypal", "uname", "", "PayPal API Username", 'This is your unique PayPal API Username assigned to your PayPal business account.');
$_SESSION['config']->setNode("paypal", "pass", "", "PayPal API Password", 'This is your unique PayPal API Password assigned to your PayPal business account.');
$_SESSION['config']->setNode("paypal", "apiKey", "", "PayPal API Key", 'This is your unique PayPal API Key assigned to your PayPal business account. You can get it by setting up your account to use the Express Checkout feature on the PayPal website.');

//Pagination Options
$_SESSION['config']->addTree("pagination", "Pagination Options");

$_SESSION['config']->setNode("pagination", "sitemapPerPage", 10, "Per Page: Sitemap", 'The number of links on each page of the site map.');
$_SESSION['config']->setNode("pagination", "categoryPerPage", 10, "Per Page: Category View", 'The number of items displayed on each page of search results.');
$_SESSION['config']->setNode("pagination", "searchPerPage", 25, "Per Page: Search Results", 'The number of links on each page of the edit sections in the Admin CP.');
$_SESSION['config']->setNode("pagination", "editItemsPerPage", 25, "Per Page: Edit Items List", 'The number of items displayed on each page in category view.');

//Account Settings
$_SESSION['config']->addTree("account", "Account Settings");

$_SESSION['config']->setNode("account", "requireAllAtSignup", false, "Require All Details at Signup", 'When enabled, users signing up to your website are required to fill in all the fields in the form. If not, then some parts of the form are optional.');
$_SESSION['config']->setNode("account", "requireEmailValidation", false, "Require Email Address Confirmation", 'When enabled, users signing up to your website are sent an email with a link, which they must click before their account is activated and they can log in.');
$_SESSION['config']->setNode("account", "validationTimeout", 48, "Email Validation Timeout (hrs)", 'When Require Email Address Confirmation is enabled, this is the amount of time the email confirmation link is valid for before the registration is cancelled and all user information is deleted.');

//Mail Settings
$_SESSION['config']->addTree("smtp", "Mail Server Settings");

$_SESSION['config']->setNode("smtp", "host", "localhost", "SMTP Server");
$_SESSION['config']->setNode("smtp", "port", 25, "SMTP Port");
$_SESSION['config']->setNode("smtp", "uname", "daemon", "Username");
$_SESSION['config']->setNode("smtp", "password", "", "Password");
$_SESSION['config']->setNode("smtp", "email", "daemon@" . preg_replace("/^www\./i", "", $_SERVER['HTTP_HOST']), "Email Address");

//Item View Options
$_SESSION['config']->addTree("viewItem", "Item View Settings");

$_SESSION['config']->setNode("viewItem", "showID", false, "Show Item ID");
$_SESSION['config']->setNode("viewItem", "imageScale", 1, "Image Scale");
$_SESSION['config']->setNode("viewItem", "catCols", 4, "Category Columns");
$_SESSION['config']->setNode("viewItem", "catTextPos", "bottom", "Category Text Position");
$_SESSION['config']->setNode("viewItem", "catChars", 30, "Category Item Summary Length");
$_SESSION['config']->setNode("viewItem", "homeTextPos", "bottom", "Home Text Position");
$_SESSION['config']->setNode("viewItem", "homeChars", 30, "Home Item Summary Length");

//Home Page Options
$_SESSION['config']->addTree("homePage", "Home Page Options");

$_SESSION['config']->setNode("homePage", "pageText", true, "Show Page Text");
$_SESSION['config']->setNode("homePage", "featuredItems", true, "Show Featured Items");
$_SESSION['config']->setNode("homePage", "popularItems", true, "Show Popular Items");
$_SESSION['config']->setNode("homePage", "latestNews", true, "Show Latest News");
$_SESSION['config']->setNode("homePage", "techTips", true, "Show Tech Tips");

//Delivery Options
$_SESSION['config']->addTree('delivery', 'Delivery Options');

//Accepts: custom, perItem, single
$_SESSION['config']->setNode('delivery', 'deliveryType', 'custom', 'Delivery Cost System');

//Save
$_SESSION['config'] = serialize($_SESSION['config']);

$setup_next_url = '../stages/paths.php';