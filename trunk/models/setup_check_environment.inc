<?php

/**
 *  This model checks the server environment for any blocking issues, and
 * builds arrays of issues appropriately. It additionally enables or disables
 * steps that require specific plugins in the $_SESSION array
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
 *  @Name models/setup_check_environment.inc
 *  @Version 1.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */
$success = array();
$fail = array();
$warn = array();

/* Root directory permissions */
if (!is_writable(dirname(__FILE__) . "/../config.php")) {
  $fail[] = "I don't have write access to config.php in the root of the site";
}


/* PHP Version */
$ver = explode('.', PHP_VERSION);
if ($ver[0] < 5) {
  $fail[] = "PHP Version 5 or later is required me to work. Please make sure the server has this installed before continuing";
} else {
  if (!PHP_VERSION >= "5.3.0") {
    $warn[] = "PHP v" . PHP_VERSION . " is supported, but it is recommended that you have version 5.3.0 or higher.";
  }
}
unset($ver);


/* MySQLi Extension */
if (!extension_loaded("mysqli")) {
  $fail[] = "The MySQLi extension is not installed. This extension is required for MySQL database access";
}


/* Curl Extension */
if (!extension_loaded("curl")) {
  $warn[] = "The CURL extension is not installed. This extension is required for the PayPal API";
  $_SESSION['additions']['curl'] = false;
} else {
  $success[] = "The CURL extension is installed and loaded - You can use the PayPal API for payment processing";
  $_SESSION['additions']['curl'] = true;
}


/* SimpleXML Extension */
if (!extension_loaded("simplexml")) {
  if (!function_exists("dl") or !dl("simplexml")) {
    $warn[] = "The SimpleXML extension is not installed. Database logs will be disabled.";
    $_SESSION['additions']['sxml'] = false;
  } else {
    $_SESSION['additions']['sxml'] = true;
  }
} else {
  $success[] = "The SimpleXML extension is installed and loaded - Detailed database logs are supported";
  $_SESSION['additions']['sxml'] = true;
}


/* GD Extension */
if (!extension_loaded("gd")) {
  $fail[] = "The GD extension is not installed. This extension is required for image manipulation";
}


/* Fileinfo Extension */
if (!extension_loaded("fileinfo")) {
  if (!function_exists("dl") or !dl("fileinfo")) {
    if (PHP_VERSION >= "5.3.0") {
      $fail[] = "The Fileinfo extension is not installed. This extension is required for general file purposes";
    } else {
      $warn[] = "The Fileinfo extension is not installed. Some functionality may be unavailable. It is required if you upgrade to PHP 5.3 later.";
    }
  }
}