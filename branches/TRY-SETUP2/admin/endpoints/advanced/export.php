<?php
require_once dirname(__FILE__)."/../header.php";
?><div class="ui-widget-header">Export</div>
<center class="ui-helper-hidden" id="exportLoading"><img src="<?php echo $config->getNode('paths','root');?>/images/loading.gif" /><br />My Database is now being exported. It might take me some time to gather all the data.<br />Important: This file is not encrypted and can easily be accessed by hackers. Make sure you keep it safe!</center>
<div>
<p>This feature generates a file containing a backup of all important data in my database, and the configuration object. It also currently has experimental support for images. Please note that depending on the size of the database, this operation can take anything from a few seconds, to several hours.</p>
<button onclick="window.location = '../process/doExport.php'; $(this.parentNode).hide(); $('#exportLoading').show();">Export Site</button>
</div></body></html>