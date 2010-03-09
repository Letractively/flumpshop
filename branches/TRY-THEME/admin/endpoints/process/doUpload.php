<?php
// Needs more comments Lloyd - Jake
require_once dirname(__FILE__)."/../header.php";
copy($_FILES['file']['tmp_name'],$config->getNode("paths","offlineDir")."/files/".$_FILES['file']['name']);
echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>File uploaded</div>";
include dirname(__FILE__)."/../advanced/upload.php";
?>