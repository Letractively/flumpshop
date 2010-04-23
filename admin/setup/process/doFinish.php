<?php
require_once dirname(__FILE__)."/../header.inc.php";
/*Create Database*/
file_put_contents(dirname(__FILE__)."/status.txt", "Testing Database Connection");
//Database Upgrader
function DBUpgrade($current_version = 1) {
	global $dbConn;
	$current_version++;
	while (file_exists(dirname(__FILE__)."/sql/DBUpgrade_v".$current_version.".sql")) {
		$qry = implode("",file(dirname(__FILE__)."/sql/DBUpgrade_v".$current_version.".sql"));
		$dbConn->multi_query($qry);
		$dbConn->query("UPDATE `stats` SET value = '$current_version' WHERE `key`='dbVer' LIMIT 1");
		$current_version++;
	}
}
//Alias needed for factory
$config = $_SESSION['config'];
$dbConn = db_factory();
if ($result = $dbConn->query("SELECT `value` FROM `stats` WHERE `key` = 'dbVer' LIMIT 1")) {
	$result = $dbConn->fetch($result);
	DBUpgrade($result['value']);
} else {
	$dbConn->multi_query(file_get_contents(dirname(__FILE__)."/sql/install.sql"),true);
	DBUpgrade(1);
}

//Save the configuration object
file_put_contents(dirname(__FILE__)."/status.txt", "Saving Configuration Object");
sleep(1);
file_put_contents(dirname(__FILE__)."/../../../conf.txt", $_SESSION['config']->getNode('paths','offlineDir')."/conf.txt");
file_put_contents($_SESSION['config']->getNode('paths','offlineDir')."/conf.txt", serialize($_SESSION['config']));
//Copy setup_files
file_put_contents(dirname(__FILE__)."/status.txt", "Copying Setup Files");
sleep(1);
$dirs = array("setup_files");
while ($dir = array_pop($dirs)) {
	$handle = opendir(dirname(__FILE__)."/$dir");
	$subdir = str_replace("setup_files","",$dir);
	while ($file = readdir($handle)) {
		if ($file != "." and $file != "..") {//. and ..
			if (is_dir(dirname(__FILE__)."/../$dir/$file")) {
				if (!is_dir($config->getNode('paths','offlineDir')."/$subdir/$file")) {
					if (!mkdir($config->getNode('paths','offlineDir')."/$subdir/$file")) {
						file_put_contents(dirname(__FILE__)."/status.txt", "Error: Couldn't create directory ".$config->getNode('paths','offlineDir')."/$subdir/$file");
					} else {
						file_put_contents(dirname(__FILE__)."/status.txt", "Created directory ".$config->getNode('paths','offlineDir')."/$subdir/$file");
					}
				}
				array_push($dirs,$dir."/$file");
			} else {
				if (!copy(dirname(__FILE__)."/../$dir/$file",$config->getNode('paths','offlineDir')."/$subdir/$file")) {
					file_put_contents(dirname(__FILE__)."/status.txt", "Error: Failed to copy ".dirname(__FILE__)."/../$dir/$file"." to ".$config->getNode('paths','offlineDir')."/$subdir/$file");
				} else {
					file_put_contents(dirname(__FILE__)."/status.txt", "Copied ".dirname(__FILE__)."/../$dir/$file"." to ".$config->getNode('paths','offlineDir')."/$subdir/$file");
				}
			}
		}
	}
}
file_put_contents(dirname(__FILE__)."/status.txt", "Cleaning Up");
unset($_SESSION);
file_put_contents(dirname(__FILE__)."/status.txt", "Finished!");
require_once dirname(__FILE__)."/../footer.inc.php";
sleep(1);
unlink(dirname(__FILE__)."/status.txt");
?>