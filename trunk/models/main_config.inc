<?php

/**
 * Load the Main Configuration Store
 */
$controller_action = 'continue';
$config = new Config('Default Configuration', true);

if ($config->getSetID() === 0) {
  $error_description = 'The Default Configuration Set does not exist.';
  $controller_action = 'abort';
}