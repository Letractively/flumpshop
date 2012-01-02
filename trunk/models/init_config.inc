<?php
//Debug Purposes Only
$INIT_DEBUG = true;
if (!$INIT_DEBUG) {
  ini_set('display_errors','Off');
} else {
  ini_set('display_errors','On');
}

/**
 * Load the Initialisation Configuration Store. If it does not exist, force
 * the setup wizard to occur
 */
$controller_action = 'continue';
if (!file_exists(dirname(__FILE__).'/../config.php')) {
  $controller_action = 'runSetup';
} else {
	include_once dirname(__FILE__).'/../config.php';
    if (!isset($initcfg)) {
      $error_description = 'The Configuration Store is corrupt or not accessible.';
      $controller_action = 'abort';
    }
}