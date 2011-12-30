<?php
$newVersion = 390; //Latest build -- IMPORTANT

$requires_tier2 = true;
$logger = true;
require_once "../endpoints/header.php";

//Get old version
$oldVer = explode(".",$config->getNode("site","version"));
$oldVer = array_reverse($oldVer);
$oldVer = $oldVer[0];

$_SESSION['upgrader']['message'] = "Creating a backup....";
$_SESSION['upgrader']['progress'] = 0;

/*
The new upgrade process:
1. Run a backup
2. Import new configuration defaults from upgrade xml file
3. Run database adjustments using Setup Wizard's DBUpgrade
4. Copy contents of /versions/$build/ to offlineDir
*/

//1. Run a backup
$storeExport = $config->getNode("paths","offlineDir")."/backup/upgrade_".$newVersion.".xml";
require_once "../endpoints/data/export.php";

$_SESSION['upgrader']['message'] = "Upgrading Configuration....";
$_SESSION['upgrader']['progress'] = 25;

//2. Import XML Configuration data
//TODO

$_SESSION['upgrader']['message'] = "Upgrading Database....";
$_SESSION['upgrader']['progress'] = 50;

//3. Run DBUpgrade
require "../setup/process/doDatabase.php";

$_SESSION['upgrader']['message'] = "Updating offlineDir...";

//4. Copy /versions/$build to offlineDir
//Do all builds between versions incase multiple version upgrades
foreach ($i = $oldVer+1;$i<$newVersion;$i++) {
	if (file_exists("versions/".$i)) {
		
		$dirs = array("versions/".$i);
		while ($dir = array_pop($dirs)) {
			$handle = opendir(dirname(__FILE__)."/$dir");
			$subdir = str_replace("versions/$i","",$dir);
			while ($file = readdir($handle)) {
				if ($file != "." and $file != "..") {//. and ..
					if (is_dir(dirname(__FILE__)."/$dir/$file")) {
						if (!is_dir($config->getNode('paths','offlineDir')."/$subdir/$file")) {
							mkdir($config->getNode('paths','offlineDir')."/$subdir/$file")
						}
						array_push($dirs,$dir."/$file");
					} else {
						copy(dirname(__FILE__)."/$dir/$file",$config->getNode('paths','offlineDir')."/$subdir/$file")
					}
				}
			}
		}
		
	}
}
?>