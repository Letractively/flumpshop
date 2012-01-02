<?php

//Alias
$config =& $_SESSION['config'];

//Separate the initialisation configuration from the base configuration
$initcfg = new Config('Flumpshop Initialisation Configuration Store', false);

$export_trees = array('database', 'paths');
foreach ($export_trees as $tree) {
  $initcfg->addTree($tree, $config->getFriendName($tree), $config->getFriendDesc($tree));
  foreach ($config->getNodes($tree) as $node) {
    $initcfg->setNode($tree, $node, $config->getNode($tree, $node), $config->getFriendName($tree, $node), $config->getFriendDesc($tree, $node));
  }
  //$config->removeTree($tree);
}
unset($export_trees);

//Write the initialisation configuration to the config.php file
$initcfg->writeAllToFile(dirname(__FILE__).'/../config.php');

//Write the configuration to the Configuration store
$dbConn = db_factory($initcfg);
$config->writeAll();