<?php

function db_factory() {
	global $config, $_SETUP;
	//MySQL
	if ($config->getNode('database','type') == "mysql") {
		//Check MySQLi Installed
		if (!extension_loaded("mysqli")) {
			if (!$_SETUP) init_err("MySQLi Extension NOT Loaded.");
		} else {
			debug_message("MySQLi Extension Installed",true);
			return new MySQL_Database($config->getNode('database','address'),$config->getNode('database','uname'),$config->getNode('database','password'),$config->getNode('database','port'),$config->getNode('database','name'));
		}
	}
	//SQLite (Experimental)
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
	
	function error() {
		return $this->lastError;
	}
	
	function time($time = 0) {
		if ($time == 0)	return date("Y-m-d H:i:s"); else return date("Y-m-d H:i:s",strtotime($time));
	}
	
	function getQueryCount() {
		return $this->queryCount;
	}
	
	function isConnected() {
		return $this->connected;
	}
	
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
	
	function deconstruct() {
		global $config;
		if ($config->getNode("temp","simplexml") && $config->getNode("logs","enabled")) {
			file_put_contents($config->getNode('paths','logDir')."/".date("d-m-Y")."/".session_id().".log.xml",$this->xmlLog->asXML());
		}
	}
}

//MySQL Extension
class MySQL_Database extends Database {
	function __construct($addr,$uname,$pass,$port,$db = NULL) {
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
	
	function __destruct() {
		if ($this->linkid) mysqli_close($this->linkid);
		$this->deconstruct(); //Parent Destructor
	}
			
	function query($str,$debug = NULL) {
		if ($debug == NULL) $debug = $this->debug;
		if (!$this->linkid) {
			trigger_error("Database Error: Cannot Execute Query - Connection Failed!");
			return false;
		}
		$this->xmlLog($str);
		$result = mysqli_query($this->linkid,$str);
		if (!$result) {
			$this->lastError = mysqli_error($this->linkid);
			trigger_error("Database Error: ".mysqli_error($this->linkid));
			$this->xmlLog($this->lastError,true);
		}
		$this->queryCount++;
		return $result;
	}
	
	function multi_query($str,$debug = NULL) {
		if ($debug == NULL) $debug = $this->debug;
		if (!$this->linkid) {
			trigger_error("Database Error: Cannot Execute Query - Connection Failed!");
			return false;
		}
		$this->xmlLog($str);
		mysqli_multi_query($this->linkid,$str);
		do {
			mysqli_next_result($this->linkid);
			$result = mysqli_store_result($this->linkid);
			if (!is_bool($result)) mysqli_free_result($result);
		} while (mysqli_more_results($this->linkid));
		return true;
	}
	
	function fetch($resource) {
		if (!is_object($resource)) {
			trigger_error("Database Error: Invalid Resource");
			return false;
		}
		return mysqli_fetch_array($resource);
	}
	
	function rows($resource) {
		if (!is_object($resource)) {
			trigger_error("Database Error: Invalid Resource");
			return 0;
		}
		return mysqli_num_rows($resource);
	}
	
	function insert_id() {
		if (!$this->linkid) {
			trigger_error("Database Error: Cannot Execute Query - Connection Failed!");
			return -1;
		}
		return mysqli_insert_id($this->linkid);
	}
}

//SQLite Extension
class SQLite_Database extends Database {
	function __construct($file) {
		$this->engine = "sqlite";
		$this->linkid = new SQLiteDatabase($file);
		$this->connected = true;
		if (!$this->linkid) {
			trigger_error("Database Error: Could not initialise - ".$this->lastError);
			$this->connected = false;
		}
		$link = $this->linkid;
		$this->Database(); //Parent Constructor
		$this->lastError = $link->lastError();
	}
	
	function __destruct() {
		$this->deconstruct(); //Parent Destructor
	}
	
	function query($str,$debug = false) {
		if (!is_object($this->linkid)) {
			trigger_error("Database Error: Cannot Execute Query - Connection Failed!");
			return false;
		}
		$str = $this->parse($str);
		$this->xmlLog($str);
		$link = $this->linkid;
		$result = $link->query($str,SQLITE_ASSOC,$error);
		if (!$result) {
			$this->lastError = $error;
			trigger_error("Database Error: ".$this->lastError);
			$this->xmlLog($this->lastError,true);
		}
		return $result;
	}
	
	function multi_query($str,$debug = NULL) {
		echo "HI";
		if ($debug == NULL) $debug = $this->debug;
		if (!$this->linkid) {
			trigger_error("Database Error: Cannot Execute Query - Connection Failed!");
			return false;
		}
		echo 1;
		$str = $this->parse($str);
		$this->xmlLog($str);
		$query = $this->linkid->queryExec($str,$err);
		if (!$query) {
			$this->lastError = $err;
			trigger_error("Database Error: ".$this->lastError);
			$this->xmlLog($this->lastError,true);
		}
		echo 2;
		return true;
	}
	
	function parse($str) {
		$str = str_ireplace(array(
								  "IF NOT EXISTS ",
								  " unsigned",
								  " zerofill",
								  " NOT NULL"
								  ),"",$str); //Unsupported & Non-vital
		$str = preg_replace("/ENGINE=.*?;/i",";",$str);
		$str = str_ireplace("AUTO_INCREMENT","AUTOINCREMENT",$str);
		//Datatypes
		$str = preg_replace("/int\([0-9]*\)/i","INTEGER",$str);
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
		if (is_object($resource)) return $resource->fetch();
		trigger_error("Database Error: Invalid Resource");
		return false;
	}
	
	function rows($resource) {
		if (!is_object($resource)) {
			trigger_error("Database Error: Invalid Resource");
			return 0;
		}
		$i = 0;
		return $resource->numRows();
	}
	
	function insert_id() {
		if (!$this->linkid) {
			trigger_error("Database Error: Cannot Execute Query - Connection Failed!");
			return -1;
		}
		return $this->linkid->lastInsertRowid();
	}
}

//MSSQL Extension
class MSSQL_Database extends Database {
	function __construct($addr,$uname,$pass,$db = NULL) {
		$this->linkid = mssql_connect($addr,$uname,$pass);
		if (!$this->linkid) {
			$this->lastError = mssql_get_last_message();
			echo "MSSQL Error: Failed to connect to MSSQL Server: ".$this->lastError;
			header("Location: /setup/?err=db_conn&dberr=".$this->lastError);
			exit();
		}
		if ($db != NULL) {
			if (!mssql_select_db($db,$this->linkid)) {
				$this->lastError = mssql_get_last_message();
				echo "MSSQL Error: Failed to select MSSQL Database: ".$this->lastError;
				header("Location: /setup/?err=db_conn&dberr=".$this->lastError);
				exit();
			}
			$this->database = $db;
		}
		$this->engine = "mssql";
		$this->Database(); //Parent Constructor
	}
	
	function query($str,$debug = false,$nativelang = false) {
		if ($debug) error_reporting(E_ALL);
		if (!$nativelang) {
			$str = str_replace("`","",$str);
			
			if (preg_match("/.* LIMIT [0-9][0-9]*,[0-9][0-9]*/i",$str)) {
				/*Advanced Limit Conversion
					1. If ordering defined, uses predefined data.
					2. If ordering is not defined, attempts to detect primary key and order by (Ascending)
				*/
				
				if (stristr($str,"ORDER BY")) {
					//STEP ONE
					//Step 1.1: Ordering Enabled (Ascending Ordering)
					$str = preg_replace("/(.*) ORDER BY (.*) ASC(ENDING)? LIMIT ([0-9][0-9]*),([0-9][0-9]*)/i",
										"SELECT * FROM (SELECT TOP $5 * FROM (SELECT TOP ($4+$5) * FROM ($1) AS SysTable1 ORDER BY $2 ASC) AS SysTable2 ORDER BY $2 DESC) AS SysTable3 ORDER BY $2 ASC;",$str);
					//Step 1.2: Ordering Enabled (Descending Ordering)
					$str = preg_replace("/(.*) ORDER BY (.*) DESC(ENDING)? LIMIT ([0-9][0-9]*),([0-9][0-9]*)/i",
										"SELECT * FROM (SELECT TOP $5 * FROM (SELECT TOP ($4+$5) * FROM ($1) AS SysTable1 ORDER BY $2 DESC) AS SysTable2 ORDER BY $2 ASC) AS SysTable3 ORDER BY $2 DESC;",$str);
				} else {
				
					//STEP TWO
					$qry = preg_replace("/SELECT .* FROM (.*) LIMIT.*/iu","EXEC sp_Pkeys $1;",$str);
					$primaryKey = $this->query($qry,false,true);
					$primaryKey = $this->fetch($primaryKey);
					$primaryKey = $primaryKey['COLUMN_NAME'];
					
					$str = preg_replace("/(.*) LIMIT ([0-9][0-9]*),([0-9][0-9]*)/i",
										"SELECT * FROM (SELECT TOP $3 * FROM (SELECT TOP ($2+$3) * FROM ($1) AS SysTable1 ORDER BY $primaryKey ASC) AS SysTable2 ORDER BY $primaryKey DESC) AS SysTable3 ORDER BY $primaryKey ASC;",$str);
				}
			}
			
			//Standard LIMIT Conversion
			$str = preg_replace("/(.*) LIMIT ([0-9][0-9]*)/i","SELECT TOP $2 * FROM ($1) AS T;",$str);
			
			/*SPECIAL FUNCTIONS*/
			//sp_databases
			if (stristr($str,"SHOW DATABASES")) {
				if ($this->query("CREATE TABLE DAL_TEMP_DBList (dbname sysname, dbsize bigint, remarks varchar NULL);",false,true))
				if ($this->query("INSERT INTO DAL_TEMP_DBList EXECUTE sp_databases;",false,true)) {
					$this->query("sp_rename 'DAL_TEMP_DBList.dbname','Database','COLUMN'",false,true);
					$return = preg_replace("/SHOW DATABASES(.*)/i","SELECT * FROM DAL_TEMP_DBList$1;",$str,1);
					$return = $this->query(str_replace(array(
															 "Database=",
															 "LIKE"),
													   array("DAL_TEMP_DBList.[Database]=",
															 "WHERE DAL_TEMP_DBList.[Database] LIKE"),$return));
					$this->query("DROP TABLE DAL_TEMP_DBList;",false,true);
					return $return;
				}
				else return false; else return false;
			}
			//sp_tables
			if (preg_match("/SHOW TABLES(.*)/ui",$str)) {
				$useDB = "";
				$tableName = "";
				$dbRegex = "/SHOW TABLES (FROM|IN) (.*)( LIKE| WHERE|\Z)/iu";
				$tableRegex = "/SHOW TABLES(.*) (LIKE |WHERE name=)(.*);*/iu";
				if (preg_match($dbRegex,$str)) $useDB = preg_replace($dbRegex,"$2.",$str,1);
				if (preg_match($tableRegex,$str)) $tableName = preg_replace($tableRegex," @table_name=$3",$str);
				$this->query("CREATE TABLE DAL_TEMP_TableList (table_qualifier sysname, table_owner sysname, table_name sysname, table_type varchar(32), remarks varchar(254) NULL);",false,true);
				$this->query("INSERT INTO DAL_TEMP_TableList EXECUTE ".$useDB."sp_tables".$tableName.";",false,true);
				$this->query("sp_rename 'DAL_TEMP_TableList.table_name','name','COLUMN';",false,true);
				$return = $this->query("SELECT name FROM DAL_TEMP_TableList;",false,true);
				$this->query("DROP TABLE DAL_TEMP_TableList;",false,true);
				return $return;
			}
		}
		
		fwrite($this->logFile,$str."\n");
		$result = mssql_query($str,$this->linkid);
		if (!$result) {
			$this->lastError = mssql_get_last_message();
			fwrite($this->logFile,"Error Occurred Executing Query: ".$this->lastError."\n");
		}
		$this->queryCount++;
		return $result;
	}
	
	function fetch($resource) {
		$result = mssql_fetch_array($resource);
		if (!$result) $this->lastError = mssql_get_last_message();
		return $result;
	}
	
	function rows($resource) {
		return mssql_num_rows($resource);
	}
}

//PostgreSQL Extension
class PGSQL_Database extends Database {
	function __construct($host,$port,$uname,$pass,$db) {
		$this->linkid = pg_connect("host='$addr' port='$port' user='$uname' password='$pass' dbname='$db'");
		if (!$this->linkid) {
			$this->lastError = pg_last_error();
			echo "PostgreSQL Error: Failed to connect to PostgreSQL Server: ".$this->lastError;
			header("Location: /setup/?err=db_conn&dberr=".$this->lastError);
			exit();
		}
		$this->engine = "postgresql";
		$this->Database(); //Parent Constructor
	}
	
	function query($str,$debug = false) {
		fwrite($this->logFile,$str."\n");
		if ($debug) error_reporting(E_ALL);
		$result = pg_query($this->linkid,$str);
		if (!$result) {
			$this->lastError = pg_last_error();
			fwrite($this->logFile,"Error Occured Executing Query: ".$this->lastError."\n");
		}
		if ($debug) error_reporting(E_ERROR);
		$this->queryCount++;
		return $result;
	}
	
	function fetch($resource) {
		$result = pg_fetch_array($resource);
		if (!$result) $this->lastError = pg_last_error();
		return $result;
	}
}
?>