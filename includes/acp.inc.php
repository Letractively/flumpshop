<?php
/**
*  Used to validate a user's access to the Administrative CP.
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
*  @Name acp.inc.php
*  @Version 1.0
*  @author Lloyd Wallis <flump5281@gmail.com>
*  @copyright Copyright (c) 2009-2012, Lloyd Wallis
*  @package Flumpshop
*/

$cached_acpPerms = array();
//Validate ACP Login
function acpusr_validate($requirement = NULL) {
	global $dbConn;
	//if ($requirement != NULL and isset($cached_acpPerms[$requirement])) return $cached_acpPerms[$requirement];
	if (!isset($_SESSION['acpusr'])) return false;
	$auth = base64_decode($_SESSION['acpusr']);
	$auth = explode("~",$auth);
	$GLOBALS['acp_uname'] = $auth[0];
	if ($requirement == NULL) {
		$result = $dbConn->query("SELECT id,pass FROM `acp_login` WHERE uname='".$auth[0]."' LIMIT 1");
	} else {
		$result = $dbConn->query("SELECT id,pass FROM `acp_login` WHERE uname='".$auth[0]."' AND $requirement=1 LIMIT 1");
	}
	if ($dbConn->rows($result) == 0) return false;
	$row = $dbConn->fetch($result);
	$GLOBALS['acp_uid'] = $row['id'];
	$valid = sha1($row['pass']) == $auth[1];
	if ($valid && $requirement != NULL) $cached_acpPerms[$requirement] = true; else $cached_acpPerms[$requirement] = false;
	return $valid;
}


//Tier 2 authentication
if (isset($requires_tier2) && $requires_tier2 == true) {
	if (!isset($_SESSION['adminAuth']) or $_SESSION['adminAuth'] == 0) {
		acpusr_tier2();
	} else {
		//Check login ID timer (15min disco)
		$result = $dbConn->query("SELECT last_tier2_login FROM `acp_login` WHERE id=".intval($_SESSION['adminAuth'])." LIMIT 1");
		if ($dbConn->rows($result) == 0) {
			acpusr_tier2();
		} else {
			$row = $dbConn->fetch($result);
			if (strtotime($row['last_tier2_login'])+900 < time()) {
				//Session expired
				acpusr_tier2();
			} else {
				//Yes, valid, extend timeout
				$dbConn->query("UPDATE `acp_login` SET last_tier2_login='".$dbConn->time()."' WHERE id=".intval($_SESSION['adminAuth'])." LIMIT 1");
			}
		}
	}
}

//Tier 2 login form
function acpusr_tier2() {
	global $config;
	if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == "on") {
		$submitPrefix = $config->getNode('paths','secureRoot');
	} else {
		$submitPrefix = $config->getNode('paths','root');
	}
	?><html><body bgcolor='#E7E7E7'><h1>Second Tier Login Required</h1><p>The operation you are trying to perform requires you to enter the Instance Managemement password. This is an extra layer of security that was hard-coded into this system when it was installed, which is needed for you to perform major tasks that could potentially break the system. Even if you know the password, make sure that you know what you are doing before you access this area.</p><p>All access to this area is logged.</p><form action='<?php echo $submitPrefix;?>/admin/tier2_auth.php' method='post'>Password:&nbsp;<input type='password' name='passkey' id='passkey' /><input type="hidden" name="return" value="<?php echo $_SERVER['REQUEST_URI'];?>" /><input type="submit" /></form></body></html><?php
	exit;
}
?>