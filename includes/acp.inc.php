<?php
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
	if ($_SERVER['HTTPS'] == "off") {
		$submitPrefix = $config->getNode('site','root');
	} else {
		$submitPrefix = $config->getNode('site','secureRoot');
	}
	?><html><body bgcolor='#E7E7E7'><h1>Second Tier Login Required</h1><p>The operation you are trying to perform requires you to enter the Instance Managemement password. This is an extra layer of security that was hard-coded into this system when it was installed, which is needed for you to perform major tasks that could potentially break the system. Even if you know the password, make sure that you know what you are doing before you access this area.</p><p>All access to this area is logged.</p><form action='<?php echo $submitPrefix;?>/admin/tier2_auth.php' method='post'>Password:&nbsp;<input type='password' name='passkey' id='passkey' /><input type="hidden" name="return" value="<?php echo $_SERVER['REQUEST_URI'];?>" /><input type="submit" /></form></body></html><?php
	exit;
}
?>