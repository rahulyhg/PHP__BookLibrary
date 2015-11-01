<?php

class LibraryDb
{
	private $dbHost;		// Host name of mySQL server
	private $dbName;		// Database name in mySQL server
	private $dbLogin;		// Login name for mySQL server
	private $dbPassword;	// Password for mySQL server
	private $dbTable;		// Array of tables' name

	/* Method defined in this class:
	Public Method:
		void __construct(void)
			- constuctor, setting default value that essential for database connection.
		boolean connect(boolean)
			- connecting to mySQL server. Argument for debug purpose.
		boolean isExist(boolean)
			- check if the database exist. Argument for debug purpose.
		boolean setTableName(string)
			- set table name which concatenate with the predefined prefix variable set in system variable.
		string getTableName(string)
			- get table name used in database.
		boolean isTableExist( $tableName )
			- check if the database exist. Argument for debug purpose.
		boolean createTable( string, string )
			- create table with the arg[0]:table name and arg[1]:table structure.
		int clearTable( string )
			- delete all record from the arg[0]:table and return the no. of records being deleted.
		boolean dropTable( $myTable )
			- drop the arg[0]:table from the database while it contains no record.
		boolean addRec( string, array )
			- add record into arg[0]:table with the arg[1]:data array.
		boolean updateRec( string, string )
			- update record into arg[0]:table with the arg[1]:sql string.
		boolean removeRec( string, string, int )
			- update record's status in arg[0]:table in which the arg[1]:id name's value = arg[2]:value.
		boolean deleteRec( $myTable, $myIdName, $MyIdValue )
			- remove from the arg[0]:table where arg[1]:id name's value = arg[2]:value.
		array getRecords( string, string, int ,int )
			- get the all records in arg[0]:table by the arg[1]:ID name in between arg[2]:min Id no and arg[3]:max Id no.
		array getRowById( string, string, string )
			- get the all columns from arg[0]:table by the arg[2]:ID name with the arg[3]:ID value.
		string getFieldDataById( string, string, string, string )
			- get the arg[0]:table's arg[1]:field data by the arg[2]:ID name with the arg[3]:ID value.
		string getFieldList( string, string )
			- get the list of value from arg[0]:table's arg[1]:field.

	Private Method:
		**none**
	*/

	public function __construct(){
		global $mysqlHost, $mysqlLogin, $mysqlPassword, $mysqlDbName;
		$this->dbHost = $mysqlHost;
		$this->dbName = $mysqlDbName;
		$this->dbLogin = $mysqlLogin;
		$this->dbPassword = $mysqlPassword;
	} // End-constructor

	public function connect( $debug = false ){
		if ( $con = mysql_connect( $this->dbHost, $this->dbLogin, $this->dbPassword ) ) {
			if ( $debug )  {
				$GLOBALS['debugMsg'] .= "<p>Connected to server.</p>";
			}

			if ( $this->isExist($debug) ){
				mysql_Select_DB( $this->dbName );
				$execution = true;
			} else {
				$GLOBALS['debugMsg'] .= "<p>System Error: Cannot select the equivalent database.</p>";
				$execution = false;
			}
		} else {
			$GLOBALS['debugMsg'] .= "<p>Error occurs when connecting to server. Please check the script and make sure the login information is correct. You may contact your server administrator for further assistant.</p>";
			$execution = false;
		}
		return $execution;
	} // End-function: connect

	public function isExist( $debug = false ){
		$sql = "select SCHEMA_NAME from INFORMATION_SCHEMA.SCHEMATA where SCHEMA_NAME = '".$this->dbName."'";
		$sqlResult = mysql_query( $sql );
		$row = mysql_fetch_array($sqlResult);
		if ( strlen($row[0]) > 0 )  {
			if ( $debug )  {
				$GLOBALS['debugMsg'] .= "<p>Database '".$row[0]."'exists.</p>";
			}
			$execution = true;
		} else {
			$execution = false;
		}
		return $execution;
	} // End-function: isExist

	public function create($myDatabase=''){
		if ( empty($myDatabase) ){
			$myDatabase = $this->dbName;
		} else {
			$this->dbName = $myDatabase;
		}
		$sql = "create database ".$myDatabase;
		$execution = mysql_query( $sql );

		return $execution;
	}

	public function drop($myDatabase=''){
		if ( empty($myDatabase) ){
			$myDatabase = $this->dbName;
		} else {
			$this->dbName = $myDatabase;
		}
		$sql = "drop database ".$myDatabase;
		$execution = mysql_query( $sql );

		return $execution;
	}

	public function getDbHost(){
		return $this->dbHost;
	}

	public function getDbName(){
		return $this->dbName;
	}

	public function getDbLogin(){
		return $this->dbLogin;
	}

	public function getDbPassword(){
		return $this->dbPassword;
	}

	public function setTableName( $tableName ){
		global $tablePrefix;
		$this->dbTable[$tableName] = $tablePrefix.'_'.$tableName;
		return true;
	} // End-function: setTableName

	public function getTableName( $tableName ){
		$tableName = $GLOBALS['tablePrefix'].'_'.$GLOBALS['table'.$tableName];
		return $tableName;
	} // End-function: getTable

	public function isTableExist( $tableName ){
		$dbTableName = $this->getTableName($tableName);
		if ( mysql_query( "desc $dbTableName" ) ){
			$execution = true;
		} else {
			$execution = false;
		}
		return $execution;
	} // End-function: isTableExist

	public function createTable( $myTable, $tableStructure ){
		$dbTableName = $this->getTableName($myTable);
		$sql = "create table $dbTableName $tableStructure";
		$execution = mysql_query( $sql );
		return $execution;
	} // End-function: createTable

	public function clearTable( $myTable )  {
		$dbTableName = $this->getTableName($myTable);
		$sql = "select count(*) from $dbTableName";
		$resource = mysql_query( $sql );
		$count = mysql_result( $resource, 0 );
		$sql = "delete from $dbTableName";
 	  	if ( $result = mysql_query( $sql ) )  {
   			$returnValue = $count;
   		} else {
   			$returnValue = null;
	   	}
        return $returnValue;
	} // End-function: clearTable

	public function dropTable( $myTable )  {
		$dbTableName = $this->getTableName($myTable);
		$count = self::countRecord( $myTable );
		$execution = false;
		if ( $count == 0 )  {
			// if no record in the table, then allow drop table.
			$sql = "drop table $dbTableName";
 			if ( $result = mysql_query( $sql ) )  {
   				$execution = true;
	   		}
	   	} else {
	   		$GLOBALS['debugMsg'] .= "<div>$myTable table still have data. Please clear the table before drop.</div>";
	   	}
        return $execution;
	}

	function addRec( $myTable, $idNo='', $args )  {
		$dbTableName = $this->getTableName($myTable);
    	$sql = "insert into $dbTableName value( '".$idNo."'";
    	if (sizeof($args)>0){
	    	foreach ( $args as $field ) {
    			$sql .= ",'".$field."'";
    		}
    	}
    	$sql .= ")";
	    $result = mysql_query( $sql );
    	if ( !empty($result) ){
	   	  $execution = true;
   		} else {
	   	  $execution = false;
   		}
	    return $execution;
	} // End-function: addRec

	public function updateRec( $myTable, $newRecord ){
		$dbTableName = $this->getTableName($myTable);
		$sql = "update $dbTableName set $newRecord";
		$execution = mysql_query( $sql );
		return $execution;
	} // End-function: updateRec

	public function removeRec( $myTable, $myClearField, $myIdName, $MyIdValue ){
		$dbTableName = $this->getTableName($myTable);
    	$sql = "update $dbTableName set $myClearField = 0 where $myIdName = $MyIdValue";
		$execution = mysql_query( $sql );
		return $execution;
	} // End-function: removeRec

	public function deleteRec( $myTable, $myIdName, $MyIdValue ){
		$dbTableName = $this->getTableName($myTable);
    	$sql = "delete from $dbTableName where $myIdName = $MyIdValue";
		$execution = mysql_query( $sql );
		return $execution;
	} // End-function: deleteRec

	public function getRecords( $myTable, $myIdName=null, $minID=null, $maxID=null ){
		/* This method allows three styles of function call:
			1) 1 args: shows all records in the table
			2) 3 args: shows only the record that match the id no provided
			3) 4 args: shows all records that the id no is between the 3rd and 4th argument
		*/
		$dbTableName = $this->getTableName($myTable);
		if ( self::isTableExist($myTable) ){
			$sql = "select * from $dbTableName";
			if (( isset($myIdName) ) && ( isset($minID) ) && ( isset($maxID) )){
				$sql .= " where $myIdName >= $minID and $myIdName <= $maxID";
			} elseif (( isset($myIdName) ) && ( isset($minID) ) ){
				if ( strpos($minID,'(') ){
					$sql .= " where $myIdName in $minID";
				} elseif ( strpos($minID,'%') ){
					$sql .= " where $myIdName like $minID";
				} else {
					$sql .= " where $myIdName = '$minID'";
				}
			}
			$resource = mysql_query( $sql );
			if ( $resource ){
				if ( mysql_num_rows($resource) > 0 ){
					$returnRec = Array();
					while ( $row = mysql_fetch_array( $resource ) ){
						array_push($returnRec,$row);
					}
				}
			}
		} else {
			$GLOBALS['debugMsg'] .= "<div>$myTable table not exist.</div>";
		}
		return $returnRec;
	} // End-function: getAllRecords

	public function getRowById( $myTable, $myIdName, $MyIdValue ){
		$dbTableName = $this->getTableName($myTable);
		$sql = "select * from $dbTableName where $myIdName = '$MyIdValue'";
		$resource = mysql_query( $sql );
		if ( $resource ){
			if ( mysql_num_rows($resource) > 0 ){
				$row = mysql_fetch_assoc( $resource );
				return $row;
			}
		} else {
			return null;
		}
	} // End-function: getRowById

	public function getFieldDataById( $myTable, $myFieldName, $myIdName, $myIdValue, $myOrderName='', $myOrder='ASC' ){
		if ( $myOrderName == '' ){ $myOrderName = $myIdName; }
		$dbTableName = $this->getTableName($myTable);
		$sql = "select $myFieldName from $dbTableName where $myIdName = '$myIdValue' order by $myOrderName $myOrder";
		$resource = mysql_query( $sql );
		if ( $resource ){
			if ( mysql_num_rows($resource) > 0 ){
				return mysql_result( $resource, 0 );
			}
		} else {
			return null;
		}
	} // End-function: getFieldDataById

	public function countRecord( $myTable, $myFieldName='', $myFieldValue='' ){
		$dbTableName = $this->getTableName($myTable);
		$sql = 'select count(*) from '.$dbTableName;
		if ( !empty($myFieldName) && !empty($myFieldValue) ){
			$sql .= " where $myFieldName = '$myFieldValue'";
		}
		$resource = mysql_query( $sql );
		$count = mysql_result( $resource, 0 );
		return $count;
	} // End-function: getFieldDataById


	public function getFieldList( $myTable, $myIdName, $myFieldName ){
		$dbTableName = $this->getTableName($myTable);
		$sql = "select $myIdName, $myFieldName from $dbTableName order by $myFieldName";
		$resource = mysql_query( $sql );
		if ( mysql_num_rows($resource) > 0 ){
			$returnRec = Array();
			while ( $row = mysql_fetch_array( $resource ) ){
				array_push($returnRec,$row);
			}
		}
		return $returnRec;
	} // End-function: getFieldDataById


} // End of class


?>