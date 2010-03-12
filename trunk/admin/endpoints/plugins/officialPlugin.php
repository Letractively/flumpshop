<?php
require_once "../header.php";
require_once "../../../includes/PluginPackage.class.php";
error_reporting(E_ALL);
//Get latest version of plugin
$version = file_get_contents("http://flumpshop.googlecode.com/svn/updater/".$_POST['pluginName'].".txt");

$fml = file_get_contents("http://flumpshop.googlecode.com/files/".$_POST['pluginName']."_v".$version.".fml");

$fml = unserialize(base64_decode($fml));

//Check plugin directory has been created
if (!file_exists($config->getNode('paths','offlineDir')."/plugins")) {
	mkdir($config->getNode('paths','offlineDir')."/plugins");
}

//Create module directory
if (!file_exists($config->getNode('paths','offlineDir')."/plugins/".$fml->moduleName)) {
	mkdir($config->getNode('paths','offlineDir')."/plugins/".$fml->moduleName);
}

//Saves recursion to have a function
function build_dir($dir) {
	global $config, $fml;
	if (!file_exists($config->getNode('paths','offlineDir')."/plugins/".$fml->moduleName."/".$dir)) {
		mkdir($config->getNode('paths','offlineDir')."/plugins/".$fml->moduleName."/".$dir);
	}
}

//Make Admin Endpoints directory
build_dir("endpoints");
//Populate
foreach ($fml->adminEndpoints as $fileName => $content) {
	file_put_contents($config->getNode('paths','offlineDir')."/plugins/".$fml->moduleName."/endpoints/".$fileName,$content);
}
//Make Admin Process directory
build_dir("process");
//Populate
foreach ($fml->adminProcess as $fileName => $content) {
	file_put_contents($config->getNode('paths','offlineDir')."/plugins/".$fml->moduleName."/process/".$fileName,$content);
}
//Make includes directory
build_dir("includes");
//Populate
foreach ($fml->includes as $fileName => $content) {
	file_put_contents($config->getNode('paths','offlineDir')."/plugins/".$fml->moduleName."/includes/".$fileName,$content);
}
//Make images directory
build_dir("images");
//Populate
foreach ($fml->images as $fileName => $content) {
	file_put_contents($config->getNode('paths','offlineDir')."/plugins/".$fml->moduleName."/images/".$fileName,$content);
}

//Run install SQL
if (!empty($fml->sql)) $dbConn->query($fml->sql);

//Install finished
echo "<h1>$fml->name</h1>";
echo "<h2>v$fml->version</h2>";
echo "<h3>Created by $fml->author</h3>";
echo "<p>$fml->notes</p>";
?><p>This plugin has now been installed. You may need to refresh the Admin CP to see new options.</p>
</body></html>