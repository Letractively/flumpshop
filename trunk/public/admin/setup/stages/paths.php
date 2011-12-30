<?php
require_once dirname(__FILE__)."/../header.inc.php";
?><h1>Paths and Directories</h1>
<form action="../process/doPaths.php" method="post"><table>
<tr>
	<td><label for="root">Home URL</label></td>
    <td><input type="text" name="root" id="root" value="<?php echo $_SESSION['config']->getNode('paths','root');?>" /></td>
    <td><span class='iconbutton' onclick='$("#rootHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="secureRoot">Secure Home URL</label></td>
    <td><input type="text" name="secureRoot" id="secureRoot" value="<?php echo $_SESSION['config']->getNode('paths','secureRoot');?>" /></td>
    <td><span class='iconbutton' onclick='$("#secureRootHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="path">Home Directory</label></td>
    <td><input type="text" name="path" id="path" value="<?php echo $_SESSION['config']->getNode('paths','path');?>" /></td>
    <td><span class='iconbutton' onclick='$("#pathHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="offlineDir">Offline Directory</label></td>
    <td><input type="text" name="offlineDir" id="offlineDir" value="<?php echo $_SESSION['config']->getNode('paths','offlineDir');?>" onChange="if (document.logDirFocus) $('#logDir').val($(this).val()+'/logs');" /></td>
    <td><span class='iconbutton' onclick='$("#offlineDirHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="logDir">Log Directory</label></td>
    <td><input type="text" name="logDir" id="logDir" value="<?php echo $_SESSION['config']->getNode('paths','logDir');?>" onFocus="document.logDirFocus = false;" /></td>
    <td><span class='iconbutton' onclick='$("#logDirHelp").dialog("open");'></span></td>
</tr>
</table>
<input type="submit" value="Continue" />
</form>
<div class="ui-helper-hidden helpDialog" id="rootHelp" title="Home URL">This is the URL of the main directory of the site. This should be automatically worked out, but check it just to be sure.</div>
<div class="ui-helper-hidden helpDialog" id="secureRootHelp" title="Secure Home URL">This is the URL of the main directory of the site when using SSL encryption. This should be automatically worked out, but check it just to be sure.</div>
<div class="ui-helper-hidden helpDialog" id="pathHelp" title="Home Directory">This is the physical location of the site on the server. This should be automatically worked out, but check it just to be sure.</div>
<div class="ui-helper-hidden helpDialog" id="offlineDirHelp" title="Offline Directory">This is the physical location of a directory on the server that Flumpshop has full permissions over. It is highly recommeneded that you ensure this folder cannot be accessed from the Internet. Make sure this directory already exists, as I won't create it for you.</div>
<div class="ui-helper-hidden helpDialog" id="logDirHelp" title="Log Directory">This is the physical location of a directory that Flumpshop has full permissions over. By default, this is a subdirectory called logs within the Offlien Direcory. Make sure this directory already exists, as I won't create it for you.</div>
<script>
$('.iconbutton').button({icons: {primary: 'ui-icon-help'}}).width('16px').height('16px');
$('.helpDialog').each(function() {$(this).dialog({autoOpen: false});});
$('input:submit').button().width('100px').css('font-size','12px');
document.logDirFocus = true;
</script><?php
require_once dirname(__FILE__)."/../footer.inc.php";?>