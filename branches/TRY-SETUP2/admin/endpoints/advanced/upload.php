<?php
require_once dirname(__FILE__)."/../header.php";
if (!file_exists($config->getNode("paths","offlineDir")."/files")) {
	mkdir($config->getNode("paths","offlineDir")."/files");
}
?><div class="ui-widget-header">File Upload</div>
<div class="ui-widget-content">
<p>This feature allows you to upload files to Flumpshop, which can then be linked to in messages, or on item/category pages.</p>
<form action="../process/doUpload.php" method="post" enctype="multipart/form-data" onsubmit="$(body).html(loadMsg('Uploading File. Please Wait...'));">
File: <input type="file" name="file" id="file" />
<input type="submit" value="Upload" style="font-size: 12px; padding: .2em .4em" />
</form>
</div>
<div class="ui-widget-header">Uploaded Files</div>
<div class="ui-widget-content"><?php
$dp = opendir($config->getNode("paths","offlineDir")."/files");
while ($file = readdir($dp)) {
	if (!is_dir($config->getNode("paths","offlineDir")."/files/".$file)) {
		echo $file." <span class='ui-icon ui-icon-trash' onclick='delFile(\"".$file."\");'></span><br />";
	}
}
?><p class="ui-state-highlight">Sample Download Code:<br />
&lt;a href='<?php echo $config->getNode("paths","root");?>/download.php?file=example.pdf'&gt;Click here to download example.pdf&lt;/a&gt;<br />
Place this in any message or on any page.
</p>
</div>
<div class="ui-helper-hidden" id="deleteDialog" title="Confirm Delete">Are you sure you want to delete <span id="deleteFile"></span>?</div>
<script type="text/javascript">
$('#deleteDialog').dialog({autoOpen: false, buttons: {Confirm: function() {window.location.href = '../process/delete.php?file='+$('#deleteFile').html();}, Cancel: function() {$(this).dialog('close');}}});
function delFile(file) {
	$('#deleteFile').html(file);
	$('#deleteDialog').dialog('open');
}
</script>
</body></html>