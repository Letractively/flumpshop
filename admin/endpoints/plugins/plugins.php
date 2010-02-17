<?php
require_once dirname(__FILE__)."/../header.php";
?><h1>Plugin Manager</h1>
<p>This feature is currently experimental, but feel free to give it a shot on test installations, and let us know how it goes.</p>
<h2>Installed Plugins</h2>
<p>This feature is not yet available.</p>
<h2>Install Plugin</h2>
<p>To install an official plugin, all you need is the name, and Flumpshop will automatically download and install it for you.</p>
<p>To install a third party plugin, upload the FML file below.</p>
<h3>Official Plugin</h3>
<form action="officialPlugin.php" method="POST">
<label for="pluginName">Module Name: </label><input type="text" name="pluginName" id="pluginName" class="ui-state-default" />
<input type="submit" value="Install" />
</form>
</body></html>