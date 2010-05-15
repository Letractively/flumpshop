<?php
require_once "../includes/vars.inc.php";
header("Content-Type: text/javascript");
header("Cache-control: max-age=3600, must-revalidate, public");
header("Expires: ".date("D, d M Y H:i:s T",time()+(3600*24)));
?>function showSignupForm() {
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
<?php
//Google Analytics
if ($config->getNode("server","analyticsID") != "") {
	?>
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
try {
var pageTracker = _gat._getTracker("<?php echo $config->getNode('server','analyticsID');?>");
pageTracker._trackPageview();
} catch(err) {}<?php
}?>