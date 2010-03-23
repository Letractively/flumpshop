<?php
require_once dirname(__FILE__)."/../header.inc.php";
$section = 'widget_carousel';
?><script>parent.leftFrame.window.location = '../?frame=leftFrame&p=3.12';</script>
<h1>Carousel Widget Settings</h1>
<form action="../process/doWidget_carousel.php" method="post">
<h2>Oops!</h2>
<p>Sorry, it looks like I made a mistake. This stage of the setup wizard is no longer needed, and the developers should remove it. Hint hint. If you still want the functionality of the Carousel widget, install the index_carousel plugin after setup.</p>
<p>You have succesfully configured this section. Click Continue to proceed.</p>
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