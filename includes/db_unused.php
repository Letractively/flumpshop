
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