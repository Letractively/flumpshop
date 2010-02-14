<?php require_once "header.inc.php";?>
<h1>Flumpshop <sup>Upgrade</sup></h1>
<p>This wizard will upgrade the Flumpshop client from v<?php echo $config->getNode("site","version");?> to v<?php echo $upgrade->newVersion();?>. Once you start this process, the Flumpnet Robot, and your Flumpshop Site, will be unavailable until the upgrade is complete. Please review all the latest details below, make any necessary changes, and if you're sure, click "Start Upgrade".</p>

<h2>Upgrade Notices</h2>
<div class="bg1">
    <p><?php echo $upgrade->getNotes();?></p>
</div>
<h2>Flumpstream Feed</h2>
<div class="bg1">
    <p>The Flumpstream News Feed is Unavailable</p>
</div>
<p><input type="button" onclick="window.location = 'settings.php';" value="Start Upgrade" class="ui-widget-content" /></p>