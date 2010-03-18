<?php
$requires_tier2 = true;
require_once dirname(__FILE__)."/../header.php";

unlink($config->getNode("paths","offlineDir")."/files/".$_GET['file']);
echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>".$_GET['file']." has been deleted.</div>";
include dirname(__FILE__)."/../advanced/upload.php";
?>