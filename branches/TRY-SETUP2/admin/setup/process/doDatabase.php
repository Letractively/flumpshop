<?php
require_once dirname(__FILE__)."/../header.inc.php";

$_SESSION['config']->setNode("database","type",$_POST['type']);
$_SESSION['config']->setNode("database","address",$_POST['address']);
$_SESSION['config']->setNode("database","port",$_POST['port']);
$_SESSION['config']->setNode("database","uname",$_POST['uname']);
$_SESSION['config']->setNode("database","password",$_POST['password']);
$_SESSION['config']->setNode("database","name",$_POST['name']);

/*Create Database*/
//Database Upgrader
function DBUpgrade($current_version = 1) {
	global $dbConn;
	$current_version++;
	while (file_exists(dirname(__FILE__)."/../sql/DBUpgrade_v".$current_version.".sql")) {
		$qry = implode("",file(dirname(__FILE__)."/../sql/DBUpgrade_v".$current_version.".sql"));
		$dbConn->multi_query($qry);
		$dbConn->query("UPDATE `stats` SET value = '$current_version' WHERE `key`='dbVer' LIMIT 1");
		$current_version++;
	}
}
//Alias needed for factory
$config = $_SESSION['config'];
file_put_contents(dirname(__FILE__)."/status.txt", "Testing Database Connection");
$dbConn = db_factory();
if (!$dbConn->connected) {
	file_put_contents(dirname(__FILE__)."/status.txt", "Database Connection Failed!");
	sleep(1);
	unlink(dirname(__FILE__)."/status.txt");
	require_once dirname(__FILE__)."/../footer.inc.php";
	exit;
}
file_put_contents(dirname(__FILE__)."/status.txt", "Database Connection Succesful. Analysing Database");
if ($result = $dbConn->query("SELECT `value` FROM `stats` WHERE `key` = 'dbVer' LIMIT 1")) {
	file_put_contents(dirname(__FILE__)."/status.txt", "Running DBUpgrade");
	$result = $dbConn->fetch($result);
	DBUpgrade($result['value']);
} else {
	file_put_contents(dirname(__FILE__)."/status.txt", "Running install.sql");
	$dbConn->multi_query(file_get_contents(dirname(__FILE__)."/../sql/install.sql"),true);
	file_put_contents(dirname(__FILE__)."/status.txt", "Running DBUpgrade");
	DBUpgrade(1);
}

file_put_contents(dirname(__FILE__)."/status.txt", "Finished!");
sleep(1); //Give time for ajax to notice change
unlink(dirname(__FILE__)."/status.txt");
require_once dirname(__FILE__)."/../footer.inc.php";
?>