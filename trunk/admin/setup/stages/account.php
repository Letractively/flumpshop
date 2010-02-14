<?php
require_once dirname(__FILE__)."/../header.inc.php";
$section = 'account';
?><script>parent.leftFrame.window.location = '../?frame=leftFrame&p=3.7';</script>
<h1>User Account Settings</h1>
<form action="../process/doAccount.php" method="post"><table><?php
foreach ($_SESSION['config']->getNodes($section) as $node) {
//Nothing special in this stage. Just use a generic handler
$type = "text";
$checked = "";
if (is_bool($_SESSION['config']->getNode($section,$node))) {
	$type = "checkbox";
	if ($_SESSION['config']->getNode($section,$node)) $checked = " checked='checked'";
}
?><tr>
	<td><label for="<?php echo $node;?>"><?php echo $_SESSION['config']->getFriendName($section, $node);?></label></td>
    <td><input type="<?php echo $type;?>" name="<?php echo $node;?>" id="<?php echo $node;?>" value="<?php echo $_SESSION['config']->getNode($section,$node);?>"<?php echo $checked;?> /></td>
    <td><span class='iconbutton' onclick='$("#<?php echo $node;?>Help").dialog("open");'></span></td>
</tr><?php
}
?></table>
<input type="submit" value="Continue" />
</form>
<div class="ui-helper-hidden helpDialog" id="enabledHelp" title="Enable Secure Transaction Processing">Whether the server should redirect to the HTTPS site for transaction processing.</div>
<div class="ui-helper-hidden helpDialog" id="adminHelp" title="Force Admin CP">Whether the server should force the Admin CP to be accessed using HTTPS.</div>
<script>
$('.iconbutton').button({icons: {primary: 'ui-icon-help'}}).width('16px').height('16px');
$('.helpDialog').each(function() {$(this).dialog({autoOpen: false});});
$('input:submit').button().width('100px').css('font-size','12px');
document.logDirFocus = true;
</script><?php
require_once dirname(__FILE__)."/../footer.inc.php";?>