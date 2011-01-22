<?php
$requires_tier2 = true;
require_once "../../../preload.php";
echo "Status: ".file_get_contents($config->getNode('paths','offlineDir')."/admin_image_rebuild_status.txt");
?>