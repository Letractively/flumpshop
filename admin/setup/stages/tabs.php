<?php
require_once dirname(__FILE__)."/../header.inc.php";
?><script>parent.leftFrame.window.location = '../?frame=leftFrame&p=3.11';</script>
<h1>Tab Settings</h1>
<form action="../process/doTabs.php" method="post"><table><?php

?><tr>
	<td><label for="homeTab">Home Tab</label></td>
    <td><input type="checkbox" name="homeTab" id="homeTab" checked="checked" /></td>
    <td><span class='iconbutton' onclick='$("#homeTabHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="homeTab">Login Tab</label></td>
    <td><input type="checkbox" name="loginTab" id="loginTab" checked="checked" /></td>
    <td><span class='iconbutton' onclick='$("#loginTabHelp").dialog("open");'></span></td>
</tr>
</table>
<input type="submit" value="Continue" />
</form>
<div class="ui-helper-hidden helpDialog" id="homeTabHelp" title="Home Tab">Whether the Home tab is displayed on the tab bar.</div>
<div class="ui-helper-hidden helpDialog" id="loginTabHelp" title="Login Tab">Whether the Login tab is displayed on the tab bar. When disabled, this essentially means that the user account feature is disabled.</div>
<script>
$('.iconbutton').button({icons: {primary: 'ui-icon-help'}}).width('16px').height('16px');
$('.helpDialog').each(function() {$(this).dialog({autoOpen: false});});
$('input:submit').button().width('100px').css('font-size','12px');
document.logDirFocus = true;
</script><?php
require_once dirname(__FILE__)."/../footer.inc.php";?>