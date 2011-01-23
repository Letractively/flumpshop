<?php
require_once dirname(__FILE__)."/../header.inc.php";
?><h1>PayPal Settings</h1>
<p>These settings allow you to process payments using PayPal Express Checkout. You need to sign up for this service at PayPal first, then fill in the details below.</p>
<form action="../process/doPaypal.php" method="post"><table>
<tr>
	<td><label for="enabled">Enable PayPal</label></td>
    <td><input type="checkbox" name="enabled" id="enabled" checked="checked" /></td>
    <td><span class='iconbutton' onclick='$("#enabledHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="uname">PayPal API Username</label></td>
    <td><input type="text" name="uname" id="uname" /></td>
    <td><span class='iconbutton' onclick='$("#unameHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="pass">PayPal API Password</label></td>
    <td><input type="password" name="pass" id="pass" /></td>
    <td><span class='iconbutton' onclick='$("#passHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="apiKey">PayPal API Key</label></td>
    <td><input type="text" name="apiKey" id="apiKey" /></td>
    <td><span class='iconbutton' onclick='$("#apiKeyHelp").dialog("open");'></span></td>
</tr>
</table>
<input type="submit" value="Continue" />
</form>
<div class="ui-helper-hidden helpDialog" id="enabledHelp" title="Home URL">When checked, Flumpshop will offer PayPal as a form of payment for your customers.</div>
<div class="ui-helper-hidden helpDialog" id="unameHelp" title="PayPal API Username">This is your unique PayPal API Username assigned to your PayPal business account.</div>
<div class="ui-helper-hidden helpDialog" id="passHelp" title="PayPal API Password">This is your unique PayPal API Password assigned to your PayPal business account.</div>
<div class="ui-helper-hidden helpDialog" id="apiKeyHelp" title="PayPal API Key">This is your unique PayPal API Key assigned to your PayPal business account.</div>
<script>
$('.iconbutton').button({icons: {primary: 'ui-icon-help'}}).width('16px').height('16px');
$('.helpDialog').each(function() {$(this).dialog({autoOpen: false});});
$('input:submit').button().width('100px').css('font-size','12px');
document.logDirFocus = true;
</script><?php
require_once dirname(__FILE__)."/../footer.inc.php";?>