<?php
$requires_tier2 = true;
$logger = true; //Relative path fix

require_once "../endpoints/header.php";
?><h1>Flumpshop Upgrade</h1>
<p>Well, if you've come here, then it's most likely that you just upgraded to a new version of Flumpshop. This page will make it as simple as possible to prepare your system to support all the changes and improvements that accompany a new version. When you're ready to begin, click the upgrade button below. Depending on the size of your database, server capacity and other factors, this process may take anything from less than a second to several minutes.</p>
<p>If you are expecting large delays as a result of this, remember you can always put the site into <span class="helper maintenanceMode">maintenance mode</span>.</p>
<button onclick="startUpgrade();$(this).addClass('ui-state-disabled');">Upgrade</button>
<div id="status"></div>
<div id="progress"></div>
<script type="text/javascript">
function startUpgrade() {
	$.ajax({url:'upgrader.php'});
	setInterval("checkUpgrade();",250);
}

function checkUpgrade() {
	$('#status').load('textStatus.php');
	$('#progress').load('numStatus.php');
}
</script>