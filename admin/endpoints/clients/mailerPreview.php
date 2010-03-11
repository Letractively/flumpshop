<?php
ob_start();
require_once dirname(__FILE__)."/../../../preload.php";
//HEADER
?><html><head><?php

//THEME
/*Print output directly - web based mail disables links*/
echo "<style type='text/css'>";
echo @file_get_contents($config->getNode('paths','offlineDir')."/themes/".$config->getNode("site","theme")."/main.css");
echo @file_get_contents($config->getNode('paths','offlineDir')."/themes/".$config->getNode("site","theme")."/mailer.css");
echo "</style>";

//TITLE BAR
?></head><body><div id="container"><div id="header"><div onClick="window.location = '<?php echo $config->getNode('paths','root');?>';"><h1 id="site_name"><?php echo $config->getNode('messages','name');?></h1><h2 id="site_tagline"><?php echo $config->getNode('messages','tagline');?></h2></div></div><!--End Header--><div id="content_container"><p>Problems viewing the email? <a href="<?php echo $config->getNode('paths','root');?>/newsletters/?id=[[[mailing_id]]]">View it on our website</a></p><br /><?php
//EMAIL CONTENT
echo $_POST['email'];


//FOOTER
?><div id='footer'><p id="footer_text"><?php echo $config->getNode('messages','footer');?></p><p id="footer_links"><a href='<?php echo $config->getNode('paths','root');?>/legal/privacy.php'>Privacy Policy</a> &middot;<a href='<?php echo $config->getNode('paths','root');?>/legal/terms.php'>Terms and Conditions</a> &middot;<a href='<?php echo $config->getNode('paths','root');?>/legal/disclaimer.php'>Disclaimer</a>&nbsp;&nbsp;</p></div><!--End Footer--><?php
//LEGAL STUFF
?><p>You have received this email after subscribing to our mailing list. If you do not wish to receive newsletters from us in future, <a href='<?php echo $config->getNode("paths","root");?>/account/unsubscribe.php'>Click here to unsubscribe</a>.</p><?php
//END CONTENT
?></div><!--End Content Container--></div><!--End Container--></body>
</html><?php
//Store page output to session
$_SESSION['newsletter_cache'] = ob_get_contents();
$_SESSION['newsletter_title'] = $_POST['title'];
ob_end_flush();
?>