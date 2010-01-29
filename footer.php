<?php require_once dirname(__FILE__)."/preload.php";
ob_flush();
?>
</td></tr></table></center>
</div>
<div id="dialog" class="ui-helper-hidden"></div>
<div id='footer'><?php echo $config->getNode('messages','footer');?>
<p><a href='<?php echo $config->getNode('paths','root');?>/legal/privacy.php'>Privacy Policy</a> &middot;
<a href='<?php echo $config->getNode('paths','root');?>/legal/terms.php'>Terms and Conditions</a> &middot;
<a href='<?php echo $config->getNode('paths','root');?>/legal/disclaimer.php'>Disclaimer</a>&nbsp;&nbsp;</p>
<!--Site Designed by Jake Mitchell. Programmed by Lloyd Wallis and John Maydew.-->
</div></div>
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
</script>
</body>
</html>