<?php // Needs more comments Lloyd - Jake
require_once "../../../preload.php";
echo "Status: ".file_get_contents($config->getNode('paths','offlineDir')."/admin_image_rebuild_status.txt");
?>