<?php

$tree = 'paths';
$additional_fields = array('config_human_name', 'config_human_description');

$data = array(
    'heading' => $_SESSION['config']->getFriendName($tree),
    'form_action' => '../process/doPaths.php',
    'fields' => $_SESSION['config']->getNodes($tree, $additional_fields)
);

foreach (array_keys($data['fields']) as $i => $j) {
  $value = $_SESSION['config']->getNode($tree, $data['fields'][$i]['config_key']);
  $data['fields'][$i]['value'] = $value;
}