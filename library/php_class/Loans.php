<?php

require_once $GLOBALS["classRoot"].'LibraryData.php';

class Loan extends LibraryData
{
	private $tableName;
	private $loan_id;
	private $transactionDate;
	private $dueDate;
	private $cardId;
	private $bookId;
	private $status;

	/* Method defined in this class:
	Public Method:
		boolean setLoanId( int )
			- set the inputted value into the variable $loan_id.
		boolean setCardId( int )
			- set the inputted value into the variable $loan_id.
		boolean setTransactionDate( string )
			- set the inputted value into the variable $loan_id.
		boolean setDueDate( string )
			- set the inputted value into the variable $loan_id.
		boolean setBookId( int )
			- set the inputted value into the variable $loan_id.
		boolean setStatus( int )
			- set the inputted value into the variable $loan_id.
		int getLoanId( void )
			- get the loan record id
		int getCardId( void )
			- get the borrower's card id
		string getTransactionDate( void )
			- get the transaction date
		string getDueDate( void )
			- get the due date
		int getBookId( void )
			- get the book id
		int getStatus( void )
			- get the status value.
		string getLoansStatus( int )
			- get the readable name of the relevant status.
		string getTableName( void)
			- get te table name in the database.
		string getTableStructure( void )
			- generate the mySQL table structure.

	Method extends from parent:
		boolean setValPosInt(ref, int)
			- set the inputted variable reference with the inputted integer while the integer is a non-zero positive interger.
		boolean setValStr(ref, string)
			- compile the inputted string for sql execution if there is any special character and save it to the inputted variable reference.
		boolean getValStr(ref, string)
			- compile the inputted string for human readable if there is any special character and save it to the inputted variable reference.
	*/

	public function __construct( $myLoanId=0, $myTransactionDate='', $myCardId=0, $myBookId=0, $myStatus=0 ){
		$this->tableName = $GLOBALS['tableLoans'];
		$this->idName = $GLOBALS['tableLoansId'];
		$this->statusNameSet = Array('Void','Borrowed','Returned');
		$this->setLoanId( $myLoanId );
 		$this->setTransactionDate( $myTransactionDate );
		$this->setCardId( $myCardId );
		$this->setBookId( $myBookId );
		$this->setStatus( $myStatus );
	}

	public function loadData( $row ){
		$this->setLoanId( $row['loan_id'] );
		$this->setTransactionDate( $row['transaction_date'] );
		$this->setCardId( $row['card_id'] );
		$this->setBookId( $row['book_id'] );
		$this->setStatus( $row['status'] );
	}

	public function setLoanId($val)   {
	  // book id start from 1 and must be a non-zero positive integer.
	  $execution = $this->setValPosInt($this->loan_id, $val);
	  return $execution;
	}

	public function setCardId($val)   {
	  // book id start from 1 and must be a non-zero positive integer.
	  $execution = $this->setValPosInt($this->cardId, $val);
	  return $execution;
	}

	public function setTransactionDate($val){
		$this->transactionDate = $val;

		$this->dueDate = date("Y-m-d", strtotime($val.' + 21 days'));
		return true;
	}
	public function setDueDate($val){
		$this->dueDate = $val;
		return true;
	}

	public function setBookId($val)   {
	  // book id start from 1 and must be a non-zero positive integer.
	  $execution = $this->setValPosInt($this->bookId, $val);
	  return $execution;
	}

	public function setStatus($val){
		$this->status = $val;
		return true;
	}

	public function getTableName()  { return $this->tableName; }
	public function getIdName()     { return $this->idName; }
	public function getLoanId()     { return $this->loan_id; }
	public function getCardId() 	{ return $this->cardId; }
	public function getTransactionDate() { return $this->transactionDate; }
	public function getDueDate()    { return $this->dueDate; }
	public function getBookId()     { return $this->bookId; }
	public function getStatus()     { return $this->status; }

	public function getLoansStatus($myCode ){
		return $this->statusNameSet[$myCode];
	}

	public function getTableStructure()  {
    	$myTableStructure =
    		" (
				".$GLOBALS['tableLoansId']."	int(10) auto_increment primary key,
				transaction_date			date,
				due_date					date,
				card_id						int(10),
				book_id						int(10),
				status						int(1),
				foreign key (card_id) references lib_Readers(".$GLOBALS['tableReadersId']."),
				foreign key (book_id) references lib_Books(".$GLOBALS['tableBooksId'].")
				) engine innodb;";
		return $myTableStructure;
	} // End-function getTableStructure

	public function newRec()  {
    	$newRecord = "
    		transaction_date = '".$this->transactionDate."',
			due_date = '".$this->dueDate."',
			card_id = ".$this->cardId.",
			book_id = ".$this->bookId.",
			status = ".$this->status."
			where ".$GLOBALS['tableLoansId']." = ".$this->loan_id;
   		return $newRecord;
	} // End-function: newRec

	function getTable( $records, $compile = '' )  {
		if ( $compile )  {
			$tableTitle = '[Loans List]';
			$actionHeader = '<div class="resultHeader">Action</div>';
		} else {
			$tableName = $this->getTableName();
			$tableTitle = "Record in table '$tableName'";
		}

		$returnValue .= "
		<div class='resultTitle'>$tableTitle</div>
			<div class='resultTable'>
				<div class='resultRow'>
		";

		if ( $compile )  {
			$returnValue .= "
				<div class='resultHeader'>Transaction Date</div>
				<div class='resultHeader'>Due Date</div>
				<div class='resultHeader'>Reader's Name</div>
				<div class='resultHeader'>Book's Name</div>
				<div class='resultHeader'>Status</div>
				$actionHeader
			</div>
			";
		} else {
			$returnValue .= '
				<div class="resultHeader">Loan ID</div>
				<div class="resultHeader">Card ID</div>
				<div class="resultHeader">Book ID</div>
				<div class="resultHeader">Transaction Date</div>
				<div class="resultHeader">Due Date</div>
				<div class="resultHeader">Status</div>
			</div>
			';
		}

		$counter = 0; // count the no of records found.
		if ( sizeof($records) > 0 )  {
			$i = 0;
			while ( $row = $records[$i++] )  {
				$counter++;
				$thisLoanId = $row['loan_id'];
				$thisTransactionDate = $row['transaction_date'];
				$thisDueDate = $row['due_date'];
				$thisCardId = $row['card_id'];
				$thisBookId = $row['book_id'];
				$thisStatus = $row['status'];

				if ( $compile )  {
					require_once $GLOBALS["classRoot"].'LibraryDb.php';
					$myLibrary = new LibraryDb();

					$thisReaderGivenName = $myLibrary->getFieldDataById('Readers', 'given_name', 'card_id', $thisCardId);
					$thisReaderSurname = $myLibrary->getFieldDataById('Readers', 'surname', 'card_id', $thisCardId);
					$thisReader = $thisReaderGivenName.' '.$thisReaderSurname;
					$thisBook = $myLibrary->getFieldDataById('Books', 'name', 'book_id', $thisBookId);
					$thisStatus = self::getLoansStatus( $thisStatus );
					$returnValue .= "
					<div class='resultRow'>
						<div class='resultData'>$thisTransactionDate</div>
						<div class='resultData'>$thisDueDate</div>
						<div class='resultDataL'>$thisReader</div>
						<div class='resultDataL'>$thisBook</div>
						<div class='resultData'>$thisStatus</div>
						<div class='resultData' style='width:94px'>
							<a class='buttonlink' href='edit_loan.php?id=$thisLoanId'>EDIT</a>
							<a class='buttonlink' href='delete_loan.php?id=$thisLoanId'>DELETE</a>
						</div>
					</div>
					";
				} else {
					$returnValue .= "
					<div class='resultRow'>
						<div class='resultData'>$thisLoanId</div>
						<div class='resultData'>$thisTransactionDate</div>
						<div class='resultData'>$thisDueDate</div>
						<div class='resultData'>$thisCardId</div>
						<div class='resultData'>$thisBookId</div>
						<div class='resultData'>$thisStatus</div>
					</div>
					";
				}
			} // End-while
			$returnValue .= '</div>';
		} else {
			$returnValue .= '</div><p class="warning">** No Table!</p>';
		}

		if ( $counter > 0 )  {
			return $returnValue;
		} else {
			return '<p class="warning">** No Record found!</p>';
		}
	}

} // End-class

?>