<?php

/**
 * Reset the setup wizard if it was aborted earlier
 */
if (!isset($_SESSION)) session_start();
if (isset($_SESSION['config'])) unset($_SESSION['config']);

//Model
require dirname(__FILE__) . '/../../../../models/setup_check_environment.inc';

//View
require dirname(__FILE__)."/../../../../includes/setup/header.inc.php";
require dirname(__FILE__) . '/../../../../views/setup_check_environment.inc';
require dirname(__FILE__)."/../../../../includes/setup/footer.inc.php";
