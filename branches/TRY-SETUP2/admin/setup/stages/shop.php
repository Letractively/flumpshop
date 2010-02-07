<?php
require_once dirname(__FILE__)."/../header.inc.php";
?><script>parent.leftFrame.window.location = '../?frame=leftFrame&p=3.1';</script>
<h1>Shop Settings</h1>
<form action="../process/doShop.php" method="post"><table>
<tr>
	<td><label for="enabled">Enable Site</label></td>
    <td><input type="checkbox" name="enabled" id="enabled" checked="checked" /></td>
    <td><span class='iconbutton' onclick='$("#enabledHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="vat">VAT Rate</label></td>
    <td><input type="text" name="vat" id="vat" value="<?php echo $_SESSION['config']->getNode('site','vat');?>" />%</td>
    <td><span class='iconbutton' onclick='$("#vatHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="shopMode">Enable Shop Mode</label></td>
    <td><input type="checkbox" name="shopMode" id="shopMode" checked="checked" /></td>
    <td><span class='iconbutton' onclick='$("#shopModeHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="sendFeedback">Enable Send Feedback</label></td>
    <td><input type="checkbox" name="sendFeedback" id="sendFeedback" checked="checked" /></td>
    <td><span class='iconbutton' onclick='$("#sendFeedbackHelp").dialog("open");'></span></td>
</tr>
</table>
<input type="submit" value="Continue" />
</form>
<div class="ui-helper-hidden helpDialog" id="enabledHelp" title="Enable Site">When unchecked, the site is placed into maintenance mode, during which only the Admin CP is accessible. The maintenance message can be customised in the Predefined Messages section.</div>
<div class="ui-helper-hidden helpDialog" id="vatHelp" title="VAT Rate">This is the percentage tax added to all products and deliveries.</div>
<div class="ui-helper-hidden helpDialog" id="shopModeHelp" title="Enable Shop Mode">When unchecked, no prices or stock levels are displayed, and the basket/checkout features are disabled.<br /><br />Shop Mode MUST be disabled when using an SQLite Database until issue 24 is fixed.</div>
<div class="ui-helper-hidden helpDialog" id="sendFeedbackHelp" title="Enable Send Feedback">When checked, a 'Send Feedback' button is added to the footer.</div>
<script>
$('.iconbutton').button({icons: {primary: 'ui-icon-help'}}).width('16px').height('16px');
$('.helpDialog').each(function() {$(this).dialog({autoOpen: false});});
$('input:submit').button().width('100px').css('font-size','12px');
document.logDirFocus = true;
</script><?php
require_once dirname(__FILE__)."/../footer.inc.php";?>