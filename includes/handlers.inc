<?php

/**
 *  These handlers are used to catch internal PHP events and deal with them
 * more gracefully
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
 *  @Name includes/handlers.inc
 *  @Version 1.0
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */

/**
 * This function is called during the initialisation of the system when a
 * catchable but unrecoverable error occurrs, e.g. database connection failure
 * @param string $msg A text representation of the error that has occurred
 * @return void Script execution ends when this function is called.
 */
function init_err($msg) {
  global $config, $_SETUP, $INIT_DEBUG;
  if (!$_SETUP && !$INIT_DEBUG) {
    /**
     * If Debug and Setup flags are not set, then go to a human-readable error
     * page. Otherwise, dump the error and quit
     */
    trigger_error($msg);
    if (is_object($config))
    /**
     * The configuration object is still available. Use an accurate path
     */
      header('Location: ' . $config->getNode('paths', 'root') .
              '/errors/500.php?err=' . base64_encode($msg) . '&config=true');
    else
    /**
     * The configuration object is not available. Make a bad guess at the path
     */
      header('Location: ./errors/500.php?err=' . base64_encode($msg));
  }
  die($msg);
}

/**
 * This is the standard non-fatal error handler for the system. This function
 * should not be called in code. Instead, trigger_error should be used
 * @global handle $errLog If defined, this function will log the error here
 * @global boolean $_PRINTDATA If true, the error will be outputted to client
 * @global boolean $ajaxProvider If false, supresses, even if $_PRINTDATA
 * @param int $level The level of the error, E_ERROR....
 * @param string $msg A textual representation of the error that has occurred
 * @param string $file The file in which the error occurred
 * @param int $line The line at which the error occurred
 * @return boolean Always returns true.
 */
function sys_error($level, $msg, $file, $line) {
  global $errLog, $_PRINTDATA, $ajaxProvider;
  if (!stristr($msg, 'Unable to load dynamic library')) {
    if ($_PRINTDATA && !$ajaxProvider)
      echo '<div class="ui-state-error ui-corner-all"><span class="ui-icon ui-icon-script"></span>' . $msg . ' (' . $file . ':' . $line . ')</div>';
    if (isset($errLog))
      fwrite($errLog, '(' . date('d/m/y H:i:s') . ') ' . $msg . ' [' . $file . ':' . $line . "]\r\n");
  }
  return true;
}

//Sets the above function as the core error handler
set_error_handler('sys_error');

/**
 * Autoloader to automatically load classes as needed. This will be called
 * throughout the script as needed
 */
function flumpshop_autoloader($class_name) {
  include_once dirname(__FILE__) . '/' . $class_name . '.class.php';
}

//Register the above function as an autoloader
spl_autoload_register('flumpshop_autoloader');
