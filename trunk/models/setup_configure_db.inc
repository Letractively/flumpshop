<?php
/**
 *  This model checks the current state of the database and installs/upgrades it
 * accordingly. It sets a $configure_log variable which views can use to output
 * a log of what this model has done.
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
 *  @Name models/setup_configure_db.inc
 *  @Version 1.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */
function DBUpgrade($current_version = 1) {
	global $dbConn, $configure_log;
	$current_version++;
	while (file_exists(dirname(__FILE__)."/../sql/DBUpgrade_v".$current_version.".sql")) {
		$configure_log .= "Running DBUpgrade (v$current_version...)<br />";
		$qry = implode("",file(dirname(__FILE__)."/../sql/DBUpgrade_v".$current_version.".sql"));
		$dbConn->multi_query($qry);
		$dbConn->query("UPDATE `stats` SET value = '$current_version' WHERE `key`='dbVer' LIMIT 1");
		$current_version++;
	}
}

$result = $dbConn->query("SELECT `value` FROM `stats` WHERE `key` = 'dbVer' LIMIT 1");

if ($result) {
	$result = $result->fetch_assoc();
    $configure_log .= 'Upgrading database from version '.$result['value'].'<br />';
	DBUpgrade($result['value']);
} else {
	$configure_log .= 'No previous installation found. Preparing database.<br />';
	$dbConn->multi_query(file_get_contents(dirname(__FILE__)."/../sql/install.sql"),true);
	$configure_log .= 'Database installed. Running upgrades.<br />';
	DBUpgrade(1);
}

$configure_log .= 'Database upgraded. Resetting values.<br />';
$dbConn->multi_query(file_get_contents(dirname(__FILE__)."/../sql/reset.sql"),true);

$configure_log .= 'Database ready.<br />';