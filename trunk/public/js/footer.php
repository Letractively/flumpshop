<?php
$ajaxProvider = true;
require_once "../../includes/preload.php";
header("Content-Type: text/javascript");
//Removed Caching - Cause GA problems?
?>function showSignupForm() {
	window.location.href = "<?php echo $initcfg->getNode('paths','root');?>/account/signup.php";
}
function loginForm() {
	loginFormHTML = '<form action="<?php echo $initcfg->getNode('paths','root');?>/account/login.php" method="post"><input type="text" name="uname" id="uname" class="ui-widget-content" value="Username" onfocus="if (this.value == \'Username\') this.value = \'\';" onblur="if (this.value == \'\') this.value = \'Username\'" /><br /><input type="password" name="password" id="password" class="ui-widget-content" value="Password" onfocus="if (this.value == \'Password\') this.value = \'\';" onblur="if (this.value == \'\') this.value = \'Password\'" /><br /><input type="submit" value="Login!" class="ui-state-default" /><input type="button" value="Sign Up" class="ui-state-highlight" onclick="$(\'#dialog\').dialog(\'destroy\');showSignupForm();" /></form>';
	
$("#dialog").html(loginFormHTML).dialog({title: "Login",buttons: {Cancel: function() {$(this).dialog('destroy');}},close: function() {$(this).dialog('destroy');}});}

$('.required').each(function() {$(this).rules("add",{messages: {required: "<?php echo str_replace("\"","\\\"",$config->getNode('messages','formFieldRequired'));?>"}})});
<?php
//Google Analytics
if ($config->getNode("server","analyticsID") != "") {
	?>var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $initcfg->getNode("server","analyticsID")?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();<?php
}?>