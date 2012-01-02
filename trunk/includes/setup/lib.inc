<?php
/**
 *  This file contains common settings/functions for the setup wizard
 *
 *  This file is part of Flumpshop.
 *
 *  Flumpshop is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Flumpshop is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with Flumpshop.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 *  @Name includes/setup/lib.inc
 *  @Version 1.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */

//Set the debug/setup flags as needed
$INIT_DEBUG = false;
$_SETUP = true;

//Verbose messages if debug is enabled
if ($INIT_DEBUG) {
  ini_set('display_errors', 'On');
  error_reporting(E_ALL);
}

//Load the required classes and prepare the configuration object
require_once dirname(__FILE__) . '/../Config.class.php';
require_once dirname(__FILE__) . '/../Database.class.php';
if (!isset($_SESSION))
  session_start();

/**
 * The configuration object is serialized again in the footer include of the
 * setup wizard. This prevents the system getting confused.
 */
if (isset($_SESSION['config']) && is_string($_SESSION['config'])) {
  $_SESSION['config'] = unserialize($_SESSION['config']);
}

/**
 * Outputs a debugging message to the client.
 * @param string $arg1 The message to output
 * @param boolean $arg2 No usage.
 */
function debug_message($arg1 = "", $arg2 = false) {
  global $INIT_DEBUG;
  if (!$INIT_DEBUG) return;
  echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-info'></span>$arg1</div>";
}

/**
 * Returns the next stage Controller to direct the client to
 * @global array $_SESSION The session.
 * @param int $stage The current stage
 * @return string The next stage to direct the client to 
 */
function getNextStage($stage) {
  global $_SESSION;
  //Security
  if ($_SESSION['stage']['security'] && $stage < 4) {
    return "security.php";
  }
  //Shop
  if ($_SESSION['stage']['shop'] && $stage < 5) {
    return "shop.php";
  }
  //PayPal
  if ($_SESSION['stage']['paypal'] && $stage < 7) {
    return "paypal.php";
  }
  //Messages
  if ($_SESSION['stage']['messages'] && $stage < 8) {
    return "messages.php";
  }
  //Pagination
  if ($_SESSION['stage']['pagination'] && $stage < 9) {
    return "pagination.php";
  }
  //Account
  if ($_SESSION['stage']['account'] && $stage < 10) {
    return "account.php";
  }
  //SMTP
  if ($_SESSION['stage']['smtp'] && $stage < 11) {
    return "smtp.php";
  }
  //Logs
  if ($_SESSION['stage']['logs'] && $stage < 12) {
    return "logs.php";
  }
  //Server
  if ($_SESSION['stage']['server'] && $stage < 13) {
    return "server.php";
  }
  //Tabs
  if ($_SESSION['stage']['tabs'] && $stage < 14) {
    return "tabs.php";
  }
  //Home Page
  if ($_SESSION['stage']['homePage'] && $stage < 15) {
    return "homePage.php";
  }
  //View Item
  if ($_SESSION['stage']['viewItem'] && $stage < 16) {
    return "viewItem.php";
  }
  return "finish.php";
}