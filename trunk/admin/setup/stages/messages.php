<?php
require_once dirname(__FILE__)."/../header.inc.php";
$section = "messages";
?><h1>Predefined Messages</h1>
<p>These messages appear all over the site. Here you can customise them all to your needs.</p>
<form action="../process/doMessages.php" method="post"><table><?php
foreach ($_SESSION['config']->getNodes($section) as $node) {
//Nothing special in this stage. Just use a generic handler
?><tr>
	<td><label for="<?php echo $node;?>"><?php echo $_SESSION['config']->getFriendName($section, $node);?></label></td>
    <td><textarea name="<?php echo $node;?>" id="<?php echo $node;?>" class='ui-widget-content' rows="6" cols="60"><?php echo $_SESSION['config']->getNode($section,$node);?></textarea></td>
    <td><!--<span class='iconbutton' onclick='$("#<?php echo $node;?>Help").dialog("open");'></span>--></td>
</tr><?php
}
?></table>
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