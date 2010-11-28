<?php

/*
* =============================================================
*  Name        : Database.class.php
*  Description : Provides a single interface for multiple
				 database engines
*  Version     : 1.1-conf
*
*  Copyright (c) 2008-2010 Lloyd Wallis, lloyd@theflump.com
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
* ====================================================================
*/

function db_factory() {
	/**
    * Creates the necessary database subclass for the Flumpshop Instance
    * @since 1.1
    * @param Uses Flumpshop Superglobals to fetch necessary details.
    * @return An instantiated Database Object
    */
	global $config, $_SETUP;
	//MySQL
	if ($GLOBALS['config']->getNode('database','type') == "mysql") {
		//Check MySQLi Installed
		if (!extension_loaded("mysqli")) {
			if (!$_SETUP) init_err("MySQLi Extension NOT Loaded.");
		} else {
			debug_message("MySQLi Extension Installed",true);
			return new MySQL_Database($GLOBALS['config']->getNode('database','address'),$GLOBALS['config']->getNode('database','uname'),$GLOBALS['config']->getNode('database','password'),$GLOBALS['config']->getNode('database','port'),$GLOBALS['config']->getNode('database','name'));
		}
	}
	//SQLite
	if ($config->getNode('database','type') == "sqlite") {
		//Check SQLite Installed
		if (!extension_loaded("sqlite")) {
			if (!$_SETUP) init_err("SQLite Extension NOT Loaded.");
		} else {
			debug_message("SQLite Extension Installed",true);
			return new SQLite_Database($config->getNode('database','address'));
		}
	}
}

/**
* The parent class for all database connection types.
*  Initialises logs and shared functions.
*/
class Database {
	var $linkid = false;
	var $engine;
	var $lastError = "No Errors have been reported by the Database Abstraction Layer.";
	var $logFile = "No resource loaded.";
	var $queryCount = 0;
	var $databaseName;
	var $debug = false;
	var $connected = true;
	var $xmlLog = "No resource loaded.";
	
	/**
    * Database constructor.
    * @since 1.0
    * @param No arguments.
    * @return No return value.
    */
	function Database() {
		global $config, $_PRINTDATA, $_DBSESLOGFILE;
		if ($config->getNode('logs','enabled') && $config->getNode("temp","simplexml")) {
			//Global Logfile removed due to MAJOR increase in load times
			if (!is_dir($config->getNode('paths','logDir')."/".date("d-m-Y")."/")) {
				mkdir($config->getNode('paths','logDir')."/".date("d-m-Y"));
			}
			if (isset($_DBSESLOGFILE)) $fname = $_DBSESLOGFILE; else $fname = session_id();
			$url = $config->getNode('paths','logDir')."/".date("d-m-Y")."/".$fname.".log.xml";
			if (!file_exists($url)) {
				file_put_contents($url,'<?xml version=\'1.0\'?><log></log>');
			}
			$this->xmlLog = new SimpleXMLElement($url,NULL,true);
		}
		$this->debug = $_PRINTDATA;
	}
	
	/**
    * Returns the last error reported by a Database subclass.
    * @since 1.0
    * @param No arguments.
    * @return Returns a string description of the last error encountered by a Database subclass
    */
	function error() {
		return $this->lastError;
	}
	
	/**
    * Builds an SQL-format timestamp.
    * @since 1.0
    * @param $time. Optional. The time which the timestamp will be created for. Defaults to now.
    * @return An SQL-formatted timestamp.
	* @todo THIS FUNCTION APPEARS TO BE VERY SLOW. AVOID USING WHERE POSSIBLE
    */
	function time($time = 0) {
		if ($time == 0)	return date("Y-m-d H:i:s"); else {
			if (!is_int($time)) $time = strtotime($time);
			return date("Y-m-d H:i:s",$time);
		}
	}
	
	/**
    * Can be used for statistical purposes.
    * @since 1.0
    * @param No arguments.
    * @return The number of queries executed by the Database subclass
    */
	function getQueryCount() {
		return $this->queryCount;
	}
	
	/**
    * Check if the database subclass connected succesfully.
    * @since 1.0
    * @param No arguments.
    * @return Boolean, true if a database connection was established, false otherwise
    */
	function isConnected() {
		return $this->connected;
	}
	
	/**
    * Internal use only. Records the specified string in the XML Log file, if enabled.
    * @since 1.1-conf
    * @param $str The message to be recorded
	* @param $error True if the message is an error
    * @return No return value.
    */
	function xmlLog($str,$error = false) {
		global $config;
		if ($config->getNode('logs','enabled') && $config->getNode("temp","simplexml")) {
			$str = htmlspecialchars($str);
			if ($error) $newChild = "error"; else $newChild = "query";
			$node = $this->xmlLog->addChild("event");
			$node->addAttribute("type", $newChild);
			$node->addChild("timestamp",date("Y-m-d H:i:s.u"));
			$node->addChild("message",$str);
		}
	}
	
	/**
    * Called by subclasses on destruct. Saves the XML log.
    * @since 1.1-conf
    * @param No arguments.
    * @return No return value.
    */
	function deconstruct() {
		global $config;
		if (!is_object($config)) return;
		if ($config->getNode("temp","simplexml") && $config->getNode("logs","enabled")) {
			file_put_contents($config->getNode('paths','logDir')."/".date("d-m-Y")."/".session_id().".log.xml",$this->xmlLog->asXML());
		}
	}
}

/**
* MySQL Extension.
* Extends the Database class for MySQLi database connections.
*/
class MySQL_Database extends Database {
	
	/**
    * Constructor.
    * @since 1.0
    * @param $addr the IP Address of FQDN of the MySQL server.
	* @param $uname the username for the MySQL server Login.
	* @param $pass the password for the MySQL server Login.
	* @param $port the port used to connect to the MySQL server. Optional (default 3306).
	* @param $db the name of the database to select. Optional (default none).
    * @return No return value
    */
	function __construct($addr,$uname,$pass,$port = 3306,$db = NULL) {
		$this->linkid = mysqli_connect($addr,$uname,$pass,$db,$port);
		$this->engine = "mysqli";
		$this->Database(); //Parent Constructor
		if (!$this->linkid) {
			$this->lastError = mysqli_connect_error();
			$this->xmlLog($this->lastError,true);
			trigger_error("MySQL Error: Failed to connect to the MySQL Server: ".$this->lastError);
			$this->connected = false;
		} else {
			$this->connected = true;
		}
		
		if ($db != NULL) {
			$this->database = $db;
		}
	}
	
	/**
    * Destructor. Disconnects from MySQL Server and calls parent Destructor.
    * @since 1.0
    * @param No arguments.
    * @return No return value.
    */
	function __destruct() {
		if ($this->linkid) mysqli_close($this->linkid);
		$this->deconstruct(); //Parent Destructor
	}
	
	/**
    * Parses and executes an SQL Query.
    * @since 1.0
    * @param $str the SQL Query.
	* @param $debug whether to output detailed information and erros. Optional (default false).
    * @return MySQLi_result set or true on success, false on failure.
    */		
	function query($str,$debug = NULL) {
		if ($debug == NULL) $debug = $this->debug;
		if (!$this->linkid) {
			trigger_error("Database Error: Cannot Execute Query - Connection Failed!");
			return false;
		}
		$this->xmlLog($str);
		$result = mysqli_query($this->linkid,$str);
		if (!$result) {
			$caller = debug_backtrace(false);
			$this->lastError = mysqli_error($this->linkid)." (Called by ".$caller[0]['file'].":".$caller[0]['line'].")";
			trigger_error("Database Error: ".mysqli_error($this->linkid)." (Called by ".$caller[0]['file'].":".$caller[0]['line'].")");
			$this->xmlLog($this->lastError,true);
		}
		$this->queryCount++;
		return $result;
	}
	
	/**
    * Execute a multi-line SQL Query on the server.
    * @since 1.1
    * @param $str the SQL Query.
	* @param $debug whether to output detailed information and erros. Optional (default false).
    * @return false if no connection, true otherwise.
    */
	function multi_query($str,$debug = NULL) {
		if ($debug == NULL) $debug = $this->debug;
		if (!$this->linkid) {
			trigger_error("Database Error: Cannot Execute Query - Connection Failed!");
			return false;
		}
		$this->xmlLog($str);
		mysqli_multi_query($this->linkid,$str);
		while (mysqli_more_results($this->linkid)) {
			mysqli_next_result($this->linkid);
			$result = mysqli_store_result($this->linkid);
			if (!is_bool($result)) mysqli_free_result($result);
		}
		return true;
	}
	
	/**
    * Fetch a row from the specified result set.
    * @since 1.0
    * @param $resource a MySQLi result resource.
    * @return An array containing a row from the result set, or false on failure.
    */
	function fetch($resource) {
		if (!is_object($resource)) {
			$caller = debug_backtrace(false);
			trigger_error('Database Error: Invalid Resource (Called by '.$caller[0]['file'].':'.$caller[0]['line'].')');
			return false;
		}
		return mysqli_fetch_array($resource);
	}
	
	/**
    * Get the number of rows in a result set.
    * @since 1.0
    * @param $resource a MySQLi result resource.
    * @return An integer specifying the number of rows in the result set (0 on failure)
    */
	function rows($resource) {
		if (!is_object($resource)) {
			$caller = debug_backtrace(false);
			trigger_error('Database Error: Invalid Resource (Called by '.$caller[0]['file'].':'.$caller[0]['line'].')');
			return 0;
		}
		return mysqli_num_rows($resource);
	}
	
	/**
    * Get the Primary Key of the last INSERT command.
    * @since 1.0
    * @param No arguments.
    * @return The ID of the last INSERT command, or -1 on failure.
    */
	function insert_id() {
		if (!$this->linkid) {
			trigger_error("Database Error: Cannot Execute Query - Connection Failed!");
			return -1;
		}
		return mysqli_insert_id($this->linkid);
	}
	
	/**
    * The version of the MySQL Server.
    * @since 1.1
    * @param No arguments.
    * @return Description of the MySQL Server environment.
    */
	function version() {
		return $this->linkid->server_info;
	}
}

//SQLite Extension
class SQLite_Database extends Database {
	function SQLite_Database($file) {
		global $_SETUP;
		$this->engine = "sqlite";
		$this->linkid = sqlite_open($file, 0666, $error);
		$this->connected = true;
		if (!$this->linkid) {
			$this->lastError = $error;
			trigger_error("Database Error: Could not initialise - ".$this->lastError);
			if ($_SETUP) {
				echo "<div class='ui-state-higlight ui-state-error'><span class='ui-icon ui-icon-alert'></span>I couldn't open $file as a SQLite Database!</div>";
			}
			$this->connected = false;
		}
		$link = $this->linkid;
		$this->Database(); //Parent Constructor
		$this->lastError = sqlite_error_string(sqlite_last_error($link));
	}
	
	function __destruct() {
		$this->deconstruct(); //Parent Destructor
	}
	
	function query($str,$debug = false) {
		if (!is_resource($this->linkid)) {
			trigger_error("Database Error: Cannot Execute Query - Connection Failed!");
			return false;
		}
		$str = $this->parse($str);
		$this->xmlLog($str);
		$link = $this->linkid;
		$result = sqlite_query($link,$str,SQLITE_ASSOC);
		if (!$result) {
			$caller = debug_backtrace();
			$this->lastError = sqlite_error_string(sqlite_last_error($link))." (Called by ".$caller[0]['file'].":".$caller[0]['line'].")";
			trigger_error("Database Error: ".$this->lastError);
			$this->xmlLog($this->lastError,true);
		}
		return $result;
	}
	
	function multi_query($str,$debug = NULL) {
		if ($debug == NULL) $debug = $this->debug;
		if (!$this->connected) {
			trigger_error("Database Error: Cannot Execute Query - Connection Failed!");
			return false;
		}
		$str = $this->parse($str);
		$this->xmlLog($str);
		$arr = explode(";",$str);
		$return = true;
		foreach ($arr as $query) {
			$query = sqlite_query($this->linkid,$query);
			if (!$query) {
				$caller = debug_backtrace(false);
				$this->lastError = sqlite_error_string(sqlite_last_error($this->linkid))." (Called by ".$caller[0]['file'].":".$caller[0]['line'].")";
				trigger_error("Database Error: ".$this->lastError);
				$this->xmlLog($this->lastError,true);
				$return = false;
			}
		}
		return $return;
	}
	
	function parse($str) {
		/*str_ireplace is PHP 5 only*/
		$str = preg_replace(array(
								  "/IF NOT EXISTS /i",
								  "/ unsigned/i",
								  "/ zerofill/i",
								  "/ NOT NULL/i"
								  ),"",$str); //Unsupported & Non-vital
		$str = preg_replace("/\) ENGINE=.*?;/i",";",$str);
		$str = preg_replace("/AUTO_INCREMENT/i","AUTOINCREMENT",$str);
		//Add Column
		$str = preg_replace_callback("/ALTER (TABLE )?`(.*?)` ADD (COLUMN )?(.*?)(;|$)/i", 'SQLite_Database_addColumn',$str);
		//Random
		$str = preg_replace("/RAND\(\)/i","RANDOM()",$str);
		//Datatypes
		$str = preg_replace("/ (tiny)?int\([0-9]*\)/i"," INTEGER",$str);
		//Limit
		$str = preg_replace("/ LIMIT [0-9](\s*)?$/i","",$str);
		//Keys
		$str = preg_replace("/,(\s*)?KEY (.*?),(\r)?\n/",",\n",$str);
		$str = preg_replace("/,(\s*)?KEY (.*?)(\r)?\n/","\n",$str);
		$str = preg_replace("/,(\s*)?UNIQUE KEY (.*?),(\r)?\n/",",\n",$str);
		$str = preg_replace("/,(\s*)?UNIQUE KEY (.*?)(\r)?\n/","\n",$str);
		//IP Encode
		$str = preg_replace_callback("/INET_ATON\(\'(.*?)\'\)/i",create_function('$matches','return "\'".ip2long($matches[1])."\'";'),$str);
		$str = str_replace("`","",$str);
		return $str;
	}
	
	function fetch($resource) {
		if (is_resource($resource)) return sqlite_fetch_array($resource);
		trigger_error("Database Error: Invalid Resource");
		return false;
	}
	
	function rows($resource) {
		if (!is_resource($resource)) {
			trigger_error("Database Error: Invalid Resource");
			return 0;
		}
		$i = 0;
		return sqlite_num_rows($resource);
	}
	
	function insert_id() {
		if (!$this->linkid) {
			trigger_error("Database Error: Cannot Execute Query - Connection Failed!");
			return -1;
		}
		return sqlite_last_insert_rowid($this->linkid);
	}
	
	function version() {
		return sqlite_libversion();
	}
}

//PHP 4 Won't let me call this using ::
function SQLite_Database_addColumn($matches) {
	//It's clunky and inefficient, but it works
	global $dbConn;
	//$matches[2] table name
	//$matches[4] new column definition
	//Copy Data
	$dbConn->query("CREATE TEMPORARY TABLE upgrade_".$matches[2]." AS SELECT * FROM ".$matches[2]);
	//Gets list of old columns
	$result = $dbConn->query("SELECT * FROM ".$matches[2]." LIMIT 0,1");
	if ($dbConn->rows($result) == 0) {
		//Empty. Try to create a row
		$dbConn->query("INSERT INTO ".$matches[2]." (ROWID) VALUES (1)");
		$result = $dbConn->query("SELECT * FROM ".$matches[2]." LIMIT 0,1");
	}
	$columns = array_keys($dbConn->fetch($result));
	$columnlist = "(";
	foreach ($columns as $column) {
		$columnlist .= $column.",";
	}
	$columnlist .= ")";
	$columnlist = str_replace(",)",")",$columnlist);
	//Get Table SQL
	$oldTable = $dbConn->query("SELECT * FROM sqlite_master WHERE type='table' AND name='".$matches[2]."'");
	$oldTable = $dbConn->fetch($oldTable);
	$dbConn->query("DROP TABLE ".$matches[2]);
	//Push new column to create query
	$sql = preg_replace("/^CREATE TABLE ".$matches[2]." \(/i", "CREATE TABLE ".$matches[2]." (".$matches[4].", ",$oldTable['sql']);
	$dbConn->query($sql);
	$dbConn->query("INSERT INTO ".$matches[2]." ".$columnlist." SELECT * FROM upgrade_".$matches[2]);
	$dbConn->query("DROP TABLE upgrade_".$matches[2]);
	return "SELECT table FROM sqlite_master LIMIT 0,1"; //Supress "Empty Query" Errors
}

?>