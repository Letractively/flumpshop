<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../../preload.php";

$orderStats = array_keys($config->getNodes('orderstatus'));

$statuses = array();

foreach ($orderStats as $status) {
	if (!is_int($status)) continue;
	$array = $config->getNode('orderstatus',$status);
	$statuses[$status] = $array['name'];
}

$statuses['selected'] = 0;

echo json_encode($statuses);
?>