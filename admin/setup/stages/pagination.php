<?php
require_once dirname(__FILE__)."/../header.inc.php";
$section = "pagination";
?><script>parent.leftFrame.window.location = '../?frame=leftFrame&p=3.6';</script>
<h1>Pagination Settings</h1>
<p>This page lets you customise the number of items displayed on each page.</p>
<form action="../process/doPagination.php" method="post"><table><?php
foreach ($_SESSION['config']->getNodes($section) as $node) {
//Nothing special in this stage. Just use a generic handler
?><tr>
	<td><label for="<?php echo $node;?>"><?php echo $_SESSION['config']->getFriendName($section, $node);?></label></td>
    <td><input type="text" name="<?php echo $node;?>" id="<?php echo $node;?>" class='ui-widget-content' rows="6" cols="60" value="<?php echo $_SESSION['config']->getNode($section,$node);?>" /></td>
    <td><span class='iconbutton' onclick='$("#<?php echo $node;?>Help").dialog("open");'></span></td>
</tr><?php
}
?></table>
<input type="submit" value="Continue" />
</form>
<div class="ui-helper-hidden helpDialog" id="sitemapPerPageHelp" title="Per Page: Sitemap">The number of links on each page of the site map.</div>
<div class="ui-helper-hidden helpDialog" id="searchPerPageHelp" title="Per Page: Search Results">The number of items displayed on each page of search results.</div>
<div class="ui-helper-hidden helpDialog" id="editItemsPerPage" title="Per Page: Edit Items List">The number of links on each page of the edit sections in the Admin CP.</div>
<div class="ui-helper-hidden helpDialog" id="categoryPerPageHelp" title="Per Page: Category View">The number of items displayed on each page in category view.</div>
<script>
$('.iconbutton').button({icons: {primary: 'ui-icon-help'}}).width('16px').height('16px');
$('.helpDialog').each(function() {$(this).dialog({autoOpen: false});});
$('input:submit').button().width('100px').css('font-size','12px');
document.logDirFocus = true;
</script><?php
require_once dirname(__FILE__)."/../footer.inc.php";?>