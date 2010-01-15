<?php
require_once dirname(__FILE__)."/../../../preload.php";
?>
<center class="ui-helper-hidden" id="exportLoading"><img src="<?php echo $config->getNode('paths','root');?>/images/loading.gif" /><br />My Database is now being exported. It might take me some time to gather all the data.<br />Important: This file is not encrypted and can easily be accessed by hackers. Make sure you keep it safe!</center>
<p>This feature generates a file containing a backup of all important data in my database. <a href="./endpoints/process/doExport.php" onclick="$(this.parentNode).hide(); $('#exportLoading').show();">Click Here</a> to begin generating this file.</p>