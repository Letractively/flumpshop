<?php
require_once dirname(__FILE__)."/../header.inc.php";
$section = 'homePage';
?><script>parent.leftFrame.window.location = '../?frame=leftFrame&p=3.12';</script>
<h1>Home Page Settings</h1>
<form action="../process/doHomePage.php" method="post"><table><?php
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
<div class="ui-helper-hidden helpDialog" id="pageTextHelp" title="Show Page Text">Toggles whether or not to show the home page message on the home page.</div>
<div class="ui-helper-hidden helpDialog" id="featuredItemsHelp" title="Show Featured Items">Toggles whether or not to show the Featured Items section on the home page.</div>
<div class="ui-helper-hidden helpDialog" id="popularItemsHelp" title="Show Popular Items">Toggles whether or not to show the Popular Items section on the home page.</div>
<div class="ui-helper-hidden helpDialog" id="latestNewsHelp" title="Show Latest News">Toggles whether or not to show the Latest News section on the home page.</div>
<div class="ui-helper-hidden helpDialog" id="techTipsHelp" title="Show Techn Tips">Toggles whether or not to show the Tech Tips section on the home page.</div>
<script>
$('.iconbutton').button({icons: {primary: 'ui-icon-help'}}).width('16px').height('16px');
$('.helpDialog').each(function() {$(this).dialog({autoOpen: false});});
$('input:submit').button().width('100px').css('font-size','12px');
document.logDirFocus = true;
</script><?php
require_once dirname(__FILE__)."/../footer.inc.php";?>