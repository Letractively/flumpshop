<?php
require_once "../header.php";
?><h1>Rebuild Images</h1>
<p>This utility should be run after changing themes or image scaling settings in the Configuration Manager in order to optimise product images. Please note that this process can take several hours.</p>
<p id="imageRebuildStatus"><a href='javascript:' onclick="imageRebuilder();">Rebuild Images</a></p>
<p>Paused? <a href="javascript:" onclick="imageRebuilder();">Click here to resume</a>. This can occur if the process exceeds the max_execution_time configured on the server. You will know this as the status will show a single image for a long time.</p>
<script type="text/javascript">
function imageRebuilder() {
	$('document').load("../process/recreateImages.php");
	$('#imageRebuildStatus').html('<img src="../../../images/loading.gif" /> Initialising...');
	if (!document.intervalStarted) {
		setInterval("$('#imageRebuildStatus').load('../process/getImageRecreateProgress.php');",500);
		document.intervalStarted = true;
	}
}
document.intervalStarted = false;
</script>
</body></html>