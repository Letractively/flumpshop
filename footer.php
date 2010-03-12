</div><!--End Content Container--><?php require_once dirname(__FILE__)."/preload.php";
echo "<!--Page Generated in ".(microtime_float()-$time_start)." Seconds-->";
ob_flush();
echo $cron;

/*PLUGINS*/
//Each plugin that has /includes/footer.inc.php will have an option displayed here
$dir = opendir($config->getNode('paths','offlineDir')."/plugins");
while ($module = readdir($dir)) {
	if (file_exists($config->getNode('paths','offlineDir')."/plugins/".$module."/includes/footer.inc.php")) {
		include $config->getNode('paths','offlineDir')."/plugins/".$module."/includes/footer.inc.php";
	}
}
?><div id="dialog" class="ui-helper-hidden"></div>
<div id='footer'><p id="footer_text"><?php echo $config->getNode('messages','footer');?></p>
<p id="footer_links"><?php
  //Send Feedback
  if ($config->getNode('site','sendFeedback')) {
	?><a href='<?php echo $config->getNode('paths','root');?>/reportbug.php'>Send Feedback</a> &middot; <?php
	}
?><a href='<?php echo $config->getNode('paths','root');?>/legal/privacy.php'>Privacy Policy</a> &middot;
<a href='<?php echo $config->getNode('paths','root');?>/legal/terms.php'>Terms and Conditions</a> &middot;
<a href='<?php echo $config->getNode('paths','root');?>/legal/disclaimer.php'>Disclaimer</a><?php
if (isset($_SESSION['adminAuth']) && $_SESSION['adminAuth']) {
	//Stats
	echo " &middot; Visitors: ";
	echo $dbConn->rows($dbConn->query("SELECT basket FROM `sessions`"));
}
?>&nbsp;&nbsp;</p><!--Site Designed by Jake Mitchell. Programmed by Lloyd Wallis and John Maydew.-->
</div><!--End Footer--></div><!--End Container-->
<script type="text/javascript">
function showSignupForm() {
	window.location.href = "<?php echo $config->getNode('paths','root');?>/account/signup.php";
}
function loginForm() {
	loginFormHTML = '<form action="<?php echo $config->getNode('paths','root');?>/account/login.php" method="post"><input type="text" name="uname" id="uname" class="ui-widget-content" value="Username" onfocus="if (this.value == \'Username\') this.value = \'\';" onblur="if (this.value == \'\') this.value = \'Username\'" /><br /><input type="password" name="password" id="password" class="ui-widget-content" value="Password" onfocus="if (this.value == \'Password\') this.value = \'\';" onblur="if (this.value == \'\') this.value = \'Password\'" /><br /><input type="submit" value="Login!" class="ui-state-default" /><input type="button" value="Sign Up" class="ui-state-highlight" onclick="$(\'#dialog\').dialog(\'destroy\');showSignupForm();" /></form>';
	
	$("#dialog").html(loginFormHTML).dialog({title: "Login",
											buttons: {
												Cancel: function() {$(this).dialog('destroy');}
												},
											close: function() {
												$(this).dialog('destroy');
											}
											});
}

$('.required').each(function() {
							   $(this).rules("add", {
											 messages: {
												required: "<?php echo str_replace("\"","\\\"",$config->getNode('messages','formFieldRequired'));?>"
											 }
											 })
							   });
</script><?php
//Google Analytics
if ($config->getNode("server","analyticsID") != "") {
	?><script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var pageTracker = _gat._getTracker("<?php echo $config->getNode('server','analyticsID');?>");
pageTracker._trackPageview();
} catch(err) {}</script><?php
}
?></body>
</html>