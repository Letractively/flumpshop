<?php
ob_start();
require_once dirname(__FILE__)."/../../../preload.php";
//HEADER
?><html>
<head><link rel="stylesheet" type="text/css" href="<?php echo $config->getNode('paths','root');?>/style/jquery.css" /><link rel="stylesheet" type="text/css" href="<?php echo $config->getNode('paths','root');?>/style/jquery-overrides.css" /><?php

//THEME
?><link rel='stylesheet' href='<?php echo $config->getNode('paths','root');?>/style/cssprovider.php?theme=<?php echo $config->getNode("site", "theme");?>&amp;sub=main' type='text/css' /><link rel='stylesheet' href='<?php echo $config->getNode('paths','root');?>/style/cssprovider.php?theme=<?php echo $config->getNode("site", "theme");?>&amp;sub=mailer' type='text/css' /><?php
?></head><body><div id="container"><div id="header"><div onclick="window.location = '<?php echo $config->getNode('paths','root');?>';"><h1 id="site_name"><?php echo $config->getNode('messages','name');?></h1><h2 id="site_tagline"><?php echo $config->getNode('messages','tagline');?></h2></div></div><!--End Header--><div id="content_container"><p>Problems viewing the email? <a href="<?php echo $config->getNode('paths','root');?>/newsletters/[[[mailing_id]]]">View it on our website</a></p><?php
//EMAIL CONTENT
echo $_POST['email'];


//FOOTER
?><div id='footer'><p id="footer_text"><?php echo $config->getNode('messages','footer');?></p><p id="footer_links"><a href='<?php echo $config->getNode('paths','root');?>/legal/privacy.php'>Privacy Policy</a> &middot;<a href='<?php echo $config->getNode('paths','root');?>/legal/terms.php'>Terms and Conditions</a> &middot;<a href='<?php echo $config->getNode('paths','root');?>/legal/disclaimer.php'>Disclaimer</a>&nbsp;&nbsp;</p></div><!--End Footer--><?php
//LEGAL STUFF
?>You have received this email after subscribing to our mailing list. If you do not wish to receive newsletters from us in future, <a href='<?php echo $config->getNode("paths","root");?>/account/unsubscribe.php'>Click here to unsubscribe</a>.<?php
//END CONTENT
?></div><!--End Content Container--></div><!--End Container--></body>
</html><?php
//Store page output to session
$_SESSION['newsletter_cache'] = ob_get_contents();
$_SESSION['newsletter_title'] = $_POST['title'];
ob_end_flush();
?>