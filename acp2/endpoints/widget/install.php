<?php
require_once dirname(__FILE__)."/../../../preload.php";
?><html>
<head><script type="text/javascript" src="../../../js/jquery.js"></script><script type="text/javascript" src="../../../js/defaults.php"></script></head>
<body>
<p>This feature allows you to install a new widget into the Flumpnet System. Simply upload the FML file format below, and I'll take care of the rest.</p>
<form action="../process/installWidget.php" method="POST" enctype="multipart/form-data" onsubmit="$(body).html(loadMsg('Installing Widget...'));">
<input type="file" name="widget" id="widget" />
<input type="submit" value="Install" class="ui-widget-content" />
</form>
</body></html>