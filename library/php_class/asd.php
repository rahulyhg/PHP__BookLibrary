<?php
/* Field declaration:
   class_level   [PK] Store the level of class of the user. Start from class 0. This field should be in auto increment.
   name          Store the class name. (E.g. Master, Undergraduate, etc)
   allowance     Shote the number of book allows to loan in this class.
*/

Class UserClass
{
	private $class_id;
	private $name;
	private $allowance;
	public function __construct( $tableName ){
	} // End-constructor

	public function setClassId( $var ){		$this->setValPosInt( $this->class_id, $val );	}

	public function setName( $var ){
		$this->setValStr( $this->name, $val );
	}

	public function setAllowance( $var ){
		$this->setValPosInt( $this->allowance, $val );
	}

	private function setValPosInt(&$var, $val){
		// set value with non-zero positive integer.
		if ( $val > 0 ){
			$var = $val;
			$execution = true;
		} else {
			$execution = false;
		}
		return $execution;
	} // End-function: setValPosInt

	private function setValStr(&$var, $val){
		// set value to sting with special character replacement.
		if ( strlen($val) > 0 ){
			$var = $val;
			$var = str_replace("'","&rsquo;",$var);
			$var = str_replace('"',"&quot;",$var);
			$execution = true;
		} else {
			$execution = false;
		}
		return $execution;
	} // End-function: setValString

	private function getValStr(&$var){
		// get back special character string.
		if ( strlen($val) > 0 ){
			$var = str_replace("&rsquo;","'",$var);
			$var = str_replace("&quot;",'"',$var);
			$execution = true;
		} else {
			$execution = false;
		}
		return $execution;
	} // End-function: getValString

	public function getTableStructure()  {
    	$myTableStructure =
    		" (
	            class_level	int(3) auto_increment primary key,
    	        name         varchar(30),
        	    allowance    int(3)
				) engine innodb;";
		return $myTableStructure;
	} // End-function getTableStructure

} // End-Class

function clearClasses()  { // Reset Object
	$tableName = getClassesTableName();
	$sql = "select count('class_level') from ".$tableName;
	$resource = mysql_query( $sql );
	$count = mysql_result( $resource, 0 );
	$sql = "delete from ".$tableName;
   	if ( $result = mysql_query( $sql ) )  {
   		$returnValue .= "<p>Table '".$tableName."' cleared. ".$count." record(s) removed.</p>";
   	} else {
   		$returnValue .= "<p>Cannot clean table '".$tableName."'. Data is link to other tables or some error occurs during process.</p>";
   	}

   	return $returnValue;
}

function getClassesList()  {
	$tableName = getClassesTableName();
	$sql = "select class_level from ".$tableName." order by name";
	$resource = mysql_query( $sql );
	while ( $result = mysql_fetch_array( $resource ) )  {
		$returnArray[] = $result[0];
	}
	return $returnArray;
}

function getClassesTable()  {
	$tableName = getClassesTableName();
	$sql = "select * from ".$tableName;
	$resource = mysql_query( $sql );

    return buildClassesTable( $resource );
}

function buildClassesTable( $resource )  {
	$tableName = getClassesTableName();
	$myResult = "
	<div class='resultTitle'>Record in table '$tableName'</div>
	  <div class='resultTable'>
	    <div class='resultRow'>
	      <div class='resultHeader'>Class Level</div>
	      <div class='resultHeader'>Class Name</div>
	      <div class='resultHeader'>Allowance</div>
	    </div>
    ";
    if ( $resource )  {
	  while ( $row = mysql_fetch_array( $resource ) )  {
		$thisClassLevel = $row['class_level'];
		$thisName = $row['name'];
		$thisAllowance = $row['allowance'];
		$myResult .= "
		<div class='resultRow'>
		  <div class='resultData'>$thisClassLevel</div>
		  <div class='resultData'>$thisName</div>
		  <div class='resultData'>$thisAllowance</div>
		</div>
		";
	  }
	  $myResult .= '</div>';
	} else {
	  $myResult .= '</div><p class="warning">** No Table!</p>';
	}
	return $myResult;
}

function addClassesSampleData()  {
	$tableName = getClassesTableName();
    $sql[0] = "insert into ".$tableName." values (1, 'Non-degree student', 3)";
    $sql[1] = "insert into ".$tableName." values (4, 'Doctor student', 20)";
    $sql[2] = "insert into ".$tableName." values (2, 'Undergraduate student', 5)";
    $sql[3] = "insert into ".$tableName." values (3, 'Master student', 10)";
    $sql[4] = "insert into ".$tableName." values (5, 'Teaching staff', 50)";
    $result = "<p><span style='font-family: courier new; font-size: 10pt'>";
    for ( $i = 0; $i <= 4; $i++ )  {
    	$result .= "SQL >> $sql[$i];<br/>";
    	$noRec += mysql_query( $sql[$i] );
    }
   	$result .= "</span>Total ".$noRec." record(s) inserted into '".$tableName."'.<br/>";
    $result .= "</p>";
    return $result;
}

function addClassesRecords()  {
	$tableName = getClassesTableName();
	if ( $_POST["classlevel"] )  {
		$newClassLevel = $_POST["classlevel"];
		$newClassName = $_POST["classname"];
		$newClassAllowance = $_POST["allowance"];
		$sql = "insert into ".$tableName." values ($newClassLevel, '$newClassName', $newClassAllowance)";
		if ( $result = mysql_query( $sql ) )  {
			$returnValue .= "<p>1 record is added into ".$tableName.".</p>";
		} else {
			$returnValue .= "<p>No record is added.</p>";
		}
	} else {
		$sql = "select max(class_level) from ".$tableName;
		$lastID = mysql_result( mysql_query( $sql ), 0 );
		$returnValue .= "<form method='post' method='".$_SERVER['PHP_SELF']."'>
		<h3 style='padding: 8px'>Add New record into table '".$tableName."'</h3>
		<p><span style='width: 200px'>Class Level: </span><input type='hidden' name='classlevel' size='3' value=".++$lastID.">".$lastID."</p>
		<p><span style='width: 200px'>Class Name: </span><input type='text' name='classname' size='30' maxchar='30'></p>
		<p><span style='width: 200px'>No. of books allows to borrow: </span><input type='text' name='allowance' size='3' maxchar='3'></p>
		<p><input type='submit' value='Add this record'></p>
		<input type='hidden' name='tablename' value='Classes'></form>";
	}

	return $returnValue;
}

?>
