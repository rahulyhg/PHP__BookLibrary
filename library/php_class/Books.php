<?php

require_once $GLOBALS["classRoot"].'LibraryData.php';

class Book extends LibraryData
{
	private $tableName;		// Table name in the database
	private $idName;		// Name of the key field
	private $book_id;		// Data in the field: book_id
	private $name;			// Data in the field: name
	private $edition;		// Data in the field: edition
	private $author;		// Data in the field: author
	private $publisher;		// Data in the field: publisher
	private $year;			// Data in the field: year
	private $status;		// Data in the field: status
	private $statusNameSet;	// Array of relevant readable name for status

	/* Method defined in this class:
	Public Method:
		void __construct(string)
			- constuctor, variable initialization.
		string getIdName(void)
			- return the id's field name of book table.
		int getBookId(void)
			- return the value of book_id.
		string getName(void)
			- return the value of the book name and compile the special character for human readable format.
		int getEdition(void)
			- return the value of the book edition.
		string getPublisher(void)
			- return the value of the publisher and compile the special character for human readable format.
		int getYear(void)
			- return the value of the year that the book being published.
		int getStatus(void)
			- return the value of the status of the book.
		string getTableName(void)
			- return the table name used in the database.
		boolean setBookId(int)
			- set the book id and validate if it is a positive integer before saving the attribute.
		boolean setName(string)
			- set the book name and compile the special character for sql execution.
		boolean setEdition(int)
			- set the book edition and validate if it is a positive integer before saving the attribute.
		boolean setAuthor(string)
			- set the author name and compile the special character for sql execution.
		boolean setPublisher(string)
			- set the publisher name and compile the special character for sql execution.
		boolean setYear(int)
			- set the publishing year.
		boolean setStatus(int)
			- set the status and validate if this is within the array range before saving the attribute.
		string getTableSructure(void)
			- generate the mySQL table structure.
		string newRec(void)
			- generate the mySQL update set.
		string getTable( string, boolean )  {
			- generate the table html code by the arg[0]:record array. If arg[1] is set to true, table will be compile to display useful data.
		string getStatusSelectMenu(int)
			- generate html code for the select menu of the status.

	Method extends from parent:
		boolean setValPosInt(ref, int)
			- set the inputted variable reference with the inputted integer while the integer is a non-zero positive interger.
		boolean setValStr(ref, string)
			- compile the inputted string for sql execution if there is any special character and save it to the inputted variable reference.
		boolean getValStr(ref, string)
			- compile the inputted string for human readable if there is any special character and save it to the inputted variable reference.
	*/

	public function __construct( $book_id='', $name='', $edition=0, $author='', $publisher='', $year=0, $status=0 ){
		$this->tableName = $GLOBALS['tableBooks'];
		$this->idName = $GLOBALS['tableBooksId'];
		$this->statusNameSet = Array('Removed','Available','On Loan');
		$this->setBookId( $book_id );
		$this->setName( $name );
		$this->setEdition( $edition );
		$this->setAuthor( $author );
		$this->setPublisher( $publisher );
		$this->setYear( $year );
		$this->setStatus( $status );
	} // End-constructor

	public function loadData( $row ){
		$this->setBookId( $row['book_id'] );
		$this->setName( $row['name'] );
		$this->setEdition( $row['edition'] );
		$this->setAuthor( $row['author'] );
		$this->setPublisher( $row['publisher'] );
		$this->setYear( $row['year'] );
		$this->setStatus( $row['status'] );
	} // End-function: loadData

	public function getIdName()   { return $this->idName; }
	public function getBookId()   { return $this->book_id; }

	public function getName()     {
		$this->getValStr( $this->name );
		return $this->name;
	} // End-function: getName

	public function getEdition()  { return $this->edition; }
	public function getAuthor()   {
		$this->getValStr( $this->author );
		return $this->author;
	} // End-function: getAuthor
	public function getPublisher(){
		$this->getValStr( $this->publisher );
		return $this->publisher;
	} // End-function: getPublisher
	public function getYear()     { return $this->year; }
	public function getStatus()   { return $this->status; }
	public function getTableName()   { return $this->tableName; }

	public function setBookId($val)   {
	  // book id start from 1 and must be a non-zero positive integer.
	  $execution = $this->setValPosInt($this->book_id, $val);
	  return $execution;
	} // End-function: setBookId

	public function setName($val)     {
	  $execution = $this->setValStr($this->name, $val);
	  return $execution;
	} // End-function: setName

	public function setEdition($val)   {
	  // edition must be a non-zero positive integer.
	  $execution = $this->setValPosInt($this->edition, $val);
	  return $execution;
	} // End-function: setEdition

	public function setAuthor($val)   {
	  $execution = $this->setValStr($this->author, $val);
	  return $execution;
	} // End-function: setAuthor

	public function setPublisher($val){
	  $execution = $this->setValStr($this->publisher, $val);
	  return $execution;
	} // End-function: setPublisher

	public function setYear($val)     {
		$this->year = $val;
		return true;
	} // End-function: setYear

	public function setStatus($val)   {
		if ( ( $val >= 0 ) && ( $val < sizeof($this->statusNameSet) ) ){
			$this->status = $val;
			$execution = true;
		} else {
			$execution = false;
		}
		return $execution;
	} // End-function: setStatus

	public function getTableStructure()  {
    	$myTableStructure =
    		" (
    			book_id       int(10) auto_increment primary key,
				name          varchar(256),
				edition       int(3),
				author        varchar(256),
				publisher     varchar(100),
				year          int(4),
				status        int(3)
			) engine innodb;";
		return $myTableStructure;
	} // End-function getTableStructure

	public function newRec()  {
    	$newRecord = "
    		name = '".$this->name."',
			edition = ".$this->edition.",
			author = '".$this->author."',
			publisher = '".$this->publisher."',
			year = ".$this->year.",
			status = ".$this->status."
			where book_id = ".$this->book_id;
   		return $newRecord;
	} // End-function: newRec

	public function getTable( $records, $compile = false )  {
		if ( $compile )  {
			$tableTitle = '[Book List]';
			$actionHeader = '<div class="resultHeader">Action</div>';
		} else {
			$tableTitle = "Record in table '".$this->tableName."'";
		}

		$returnValue = "
<div class='resultTitle'>$tableTitle</div>
	<div class='resultTable'>
		<div class='resultRow'>
		<div class='resultHeader'>Name</div>
		<div class='resultHeader'>Author</div>
		<div class='resultHeader'>Publisher</div>
		<div class='resultHeader'>Edition</div>
		<div class='resultHeader'>Publish Year</div>
		<div class='resultHeader'>Status</div>
		$actionHeader
	</div>
";

		$counter = 0; // count the no of records found.

		if ( sizeof($records) > 0 )  {
			$i = 0;
			while ( $row = $records[$i++] )  {
				$counter++;
				$thisBook = new Book( $this->tableName );
				$thisBook->setBookId( $row['book_id'] );
				$thisBook->setName( $row['name'] );
				$thisBook->setPublisher( $row['publisher'] );
				$thisBook->setAuthor( $row['author'] );
				$thisBook->setEdition( $row['edition'] );
				$thisBook->setYear( $row['year'] );
				$thisBook->setStatus( $row['status'] );
				if ( $compile )  {
					$thisBook->setStatus( $this->statusNameSet[$thisBook->getStatus()] );
					$actionButton = "
<div class='resultData' style='width:94px'>
	<a class='buttonlink' href='edit_book.php?id=".$thisBook->getBookId()."'>EDIT</a>
	<a class='buttonlink' href='delete_book.php?id=".$thisBook->getBookId()."'>DELETE</a>
</div>
";
				} // End-if
				$returnValue .= "
<div class='resultRow'>
	<div class='resultDataL'>".$thisBook->getName()."</div>
	<div class='resultDataL'>".$thisBook->getAuthor()."</div>
	<div class='resultDataL'>".$thisBook->getPublisher()."</div>
	<div class='resultData'>".$thisBook->getEdition()."</div>
	<div class='resultData'>".$thisBook->getYear()."</div>
	<div class='resultData'>".$thisBook->getStatus()."</div>
	$actionButton
</div>
";
			} // End-while
		} else {
			$GLOBALS['debugMsg'] .= '<p>Book table not exist!</p>';
		}
		$returnValue .= '</div>';

		if ( $counter > 0 )  {
			return $returnValue;
		} else {
			return '<p class="warning">** No Record found!</p>';
		}
	} // End-function: buildBooksTable

	public function getStatusSelectMenu($statusId=null){
		$selected[$statusId] = ' selected';
		$returnValue = '
<select name="status">
	<option>Please select</option>
	<option>-------------</option>
';
		for ( $i=0; $i<sizeof($this->statusNameSet); $i++){
			$returnValue .= '<option value="'.$i.'"'.$selected[$i].'>'.$this->statusNameSet[$i].'</option>';
		}
		$returnValue .= '</select>';

		return $returnValue;
	} // End-function: getStatusSelectMenu


} // End-class

?>