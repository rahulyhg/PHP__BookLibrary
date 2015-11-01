<?php

require_once $GLOBALS["classRoot"].'LibraryData.php';

Class UserClass extends LibraryData
{
	private $tableName;			// Table name in the database
	private $idName;			// Name of the key field
	private $class_id;			// Data in field: class_id
	private $name;				// Data in field: name
	private $allowance;			// Data in field: allowance

	/* Method defined in this class:
	Public Method:
		void __construct(string, string, int, string, int)
			- constuctor, variable initialization.
		boolean setClassId(int)
			- validate & save incoming data into the variable $class_id.
		boolean setName(string)
			- compile & save incoming data into the variable $name.
		boolean setAllowance(int)
			- validate & save incoming data into the variable allowance.
		string getTableName(void)
			- return the table name for storing user classes records.
		string getIdName(void)
			- return the id's field name of table.
		string getClassId(void)
			- return the class id value.
		string getName(void)
			- return the name value.
		string getAllowance(void)
			- return the allowance value.
		string getTableSructure(void)
			- generate the mySQL table structure.
		string getTable( string )  {
			- generate the table html code by the arg[0]:record array.

	Method extends from parent:
		boolean setValPosInt(ref, int)
			- set the inputted variable reference with the inputted integer while the integer is a non-zero positive interger.
		boolean setValStr(ref, string)
			- compile the inputted string for sql execution if there is any special character and save it to the inputted variable reference.
		boolean getValStr(ref, string)
			- compile the inputted string for human readable if there is any special character and save it to the inputted variable reference.
	*/
	public function __construct( $myClassId=0, $myName='', $myAllowance=0 ){		$this->tableName = $GLOBALS['tableUserClasses'];		$this->idName = $GLOBALS['tableUserClassesId'];
		$this->setClassId( $myClassId );
		$this->setName( $myName );
		$this->setAllowance( $myAllowance );
	} // End-constructor

	public function setClassId( $val ){		$execution = $this->setValPosInt( $this->class_id, $val );
		return $execution;	} // End-function: setClassId

	public function setName( $val ){
		$execution = $this->setValStr( $this->name, $val );
		return $execution;
	} // End-function: setName

	public function setAllowance( $val ){
		$execution = $this->setValPosInt( $this->allowance, $val );
		return $execution;
	} // End-function: setAllowance

	public function getTableName(){		return $this->tableName;	} // End-function: getTableName

	public function getIdName(){
		return $this->idName;
	} // End-function: getIdName

	public function getClassId(){		return $this->class_id;
	} // End-function: getClassId

	public function getName(){
		return $this->name;
	} // End-function: getIdName

	public function getAllowance(){
		return $this->allowance;
	} // End-function: getIdName

	public function getTableStructure()  {
		$myTableStructure =
			" (
				class_id	int(3) auto_increment primary key,
				name		varchar(30),
				allowance	int(3)
				) engine innodb;";
		return $myTableStructure;
	} // End-function: getTableStructure

	public function getTable($records)  {		$returnValue = '
		<div class="resultTitle">Record in table \''.$this->tableName.'\'</div>
			<div class="resultTable">
				<div class="resultRow">
					<div class="resultHeader">Class ID</div>
					<div class="resultHeader">Class Name</div>
					<div class="resultHeader">Allowance</div>
				</div>
		';
		if ( sizeof($records) > 0 )  {
			$i = 0;
			while ( $row = $records[$i++] )  {
				$counter++;
				$thisTable = new UserClass( $this->tableName );
				$thisTable->setClassId( $row['class_id'] );
				$thisTable->setName( $row['name'] );
				$thisTable->setAllowance( $row['allowance'] );

				$returnValue .= '
		<div class="resultRow">
			<div class="resultData">'.$thisTable->getClassId().'</div>
			<div class="resultData">'.$thisTable->getName().'</div>
			<div class="resultData">'.$thisTable->getAllowance().'</div>
		</div>
		';
	  		}
			$returnValue .= '</div>';
		} else {
			$GLOBALS['debugMsg'] .= '<p>Book table not exist!</p>';
		}
		return $returnValue;
	} // End-function: getTable

} // End-Class

?>
