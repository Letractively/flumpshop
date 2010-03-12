<?php
class PluginPackage {
	var $adminEndpoints = array(); //An array of Admin Endpoints
	var $adminProcess = array(); //An array of Admin Processors
	var $includes = array(); //An array of include files
	var $images = array(); //An array of PNG (MUST BE) files
	var $version = array(); //Plugin version
	var $name = array(); //Plugin name (Friendly)
	var $moduleName = array(); //Plugin name (Server-side)
	var $author = array(); //Plugin author
	var $sql = array(); //SQL Query to run on install
	var $notes = array(); //Install notes
}
?>