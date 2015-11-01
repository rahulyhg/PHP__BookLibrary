<?php

require_once $GLOBALS["classRoot"].'LibraryData.php';

class Reader extends LibraryData
{
	private $tableName;			// Table name in the database
	private $idName;			// Name of the key field
	private $card_id;			// Data in the field: card_id
	private $given_name;		// Data in the field: given_name
	private $surname;			// Data in the field: surname
	private $register_date;		// Data in the field: register_date
	private $tel;				// Data in the field: tel
	private $id_cert;			// Data in the field: id_cert
	private $activated;			// Data in the field: activated
	private $classId;				// Data in the field: classId

	/* Method defined in this class:
	Public Method:

	Public Method:
		boolean setValPosInt(ref, int)
			- set the inputted variable reference with the inputted integer while the integer is a non-zero positive interger.
		boolean setValStr(ref, string)
			- compile the inputted string for sql execution if there is any special character and save it to the inputted variable reference.
		boolean getValStr(ref, string)
			- compile the inputted string for human readable if there is any special character and save it to the inputted variable reference.

	Method extends from parent:
		boolean setValPosInt(ref, int)
			- set the inputted variable reference with the inputted integer while the integer is a non-zero positive interger.
		boolean setValStr(ref, string)
			- compile the inputted string for sql execution if there is any special character and save it to the inputted variable reference.
		boolean getValStr(ref, string)
			- compile the inputted string for human readable if there is any special character and save it to the inputted variable reference.
	*/


	public function __construct( $myCardId=0, $myGivenName='', $mySurname='', $myRegisterDate='',
								 $myTel='', $myIdCert='', $myClassId=0, $myActivated=0 ){
		$this->tableName = $GLOBALS['tableReaders'];
		$this->idName = $GLOBALS['tableReadersId'];
		$this->setCardId( $myCardId );
		$this->setGivenName( $myGivenName );
		$this->setSurname( $mySurname );
		$this->setRegisterDate( $myRegisterDate );
		$this->setTel( $myTel );
		$this->setIdCert( $myIdCert );
		$this->setClassId( $myClassId );
		$this->setActivated( $myActivated );
	}

	public function loadData( $row ){
		$this->setCardId( $row['card_id'] );
		$this->setGivenName( $row['given_name'] );
		$this->setSurname( $row['surname'] );
		$this->setRegisterDate( $row['register_date'] );
		$this->setTel( $row['tel'] );
		$this->setIdCert( $row['id_cert'] );
		$this->setClassId( $row['class_id'] );
		$this->setActivated( $row['activated'] );
	}

	public function getTableName()   { return $this->tableName; }
	public function getIdName()   { return $this->idName; }
	public function getCardId()   { return $this->card_id; }
	public function getGivenName(){
		$this->getValStr( $this->given_name );
		return $this->given_name;
	}
	public function getSurname(){
		$this->getValStr( $this->surname );
		return $this->surname;
	}
	public function getRegisterDate()   { return $this->register_date; }
	public function getTel(){ return $this->tel; }
	public function getIdCert()     { return $this->id_cert; }
	public function getActivated()   { return $this->activated; }
	public function getClassId()   { return $this->classId; }

	public function setCardId($val)   {
	  // book id start from 1 and must be a non-zero positive integer.
	  $returnValue = $this->setValPosInt($this->card_id, $val);
	  return $returnValue;
	}

	public function setGivenName($val){		$execution = $this->setValStr($this->given_name, $val);
		return $execution;
	}
	public function setSurname($val){
		$execution = $this->setValStr($this->surname, $val);
		return $execution;
	}
	public function setRegisterDate($val)   { $this->register_date = $val; }
	public function setTel($val){ $this->tel = $val; }
	public function setIdCert($val)     { $this->id_cert = $val; }
	public function setActivated($val)   { $this->activated = $val; }
	public function setClassId($val)   { $this->classId = $val; }

	public function getTableStructure()  {
    	$myTableStructure = "
			(
				card_id			int(10) auto_increment primary key,
				given_name		varchar(30),
				surname			varchar(20),
				register_date	date,
				tel				varchar(16),
				id_cert			varchar(12),
				class_id		int(3),
				activated		boolean,
				foreign key (class_id) references ".$GLOBALS['tablePrefix'].'_'.$GLOBALS['tableUserClasses'].'('.$GLOBALS['tableUserClassesId'].")
			) engine innodb auto_increment = 100100;";
		return $myTableStructure;
	} // End-function getTableStructure

	public function newRec()  {
    	$newRecord = "
    		given_name = '".$this->given_name."',
			surname = '".$this->surname."',
			register_date = '".$this->register_date."',
			tel = '".$this->tel."',
			id_cert = '".$this->id_cert."',
			class_id = ".$this->classId.",
			activated = ".$this->activated."
			where card_id = ".$this->card_id;
   		return $newRecord;
	} // End-function: newRec

	function getTable( $records, $compile = '' )  {
		$tableName = $this->getTableName();
		if ( $compile )  {
			$tableTitle = '[Readers List]';
			$classHeader = 'Class ID';
			$transformHeader = '
				<div class="resultHeader">Quota Used</div>
					<div class="resultHeader">Action</div>';
		} else {
			$tableTitle = "Record in table '$tableName'";
			$classHeader = 'Class';
		}

		$tableHeader = "
			<div class='resultTitle'>$tableTitle</div>
				<div class='resultTable'>
					<div class='resultRow'>
						<div class='resultHeader'>Card ID</div>
						<div class='resultHeader'>Given Name</div>
						<div class='resultHeader'>Surname</div>
						<div class='resultHeader'>Register Date</div>
						<div class='resultHeader'>Tel No.</div>
						<div class='resultHeader'>Id Cert. No.</div>
						<div class='resultHeader'>$classHeader</div>
						<div class='resultHeader'>Activate</div>
						$transformHeader
					</div>
		";

		if ( sizeof($records) > 0 )  {
			$i = 0;
			while ( $row = $records[$i++] )  {
				$counter++;
				$thisCardId = $row['card_id'];
				$thisGivenName = $row['given_name'];
				$thisSurname = $row['surname'];
				$thisRegister_date = $row['register_date'];
				$thisTel = $row['tel'];
				$thisIdCert = $row['id_cert'];
				$thisClass = $row['class_id'];
				$thisActivated = $row['activated'];

				if ( $compile )  {	 				require_once $GLOBALS["classRoot"].'LibraryDb.php';

	 				$myLibrary = new LibraryDb();
					$thisQuota = $myLibrary->getFieldDataById('UserClasses','allowance','class_id',$thisClass);
					$thisClass = $myLibrary->getFieldDataById('UserClasses','name','class_id',$thisClass);
					$thisReaderBorrowed = $myLibrary->countRecord('Loans','card_id',$thisCardId.'\' and status = \'1');
					$thisReaderReturned = $myLibrary->countRecord('Loans','card_id',$thisCardId.'\' and status = \'2');
					$thisQuotaUsed = $thisReaderBorrowed - $thisReaderReturned;

					$activatedMsg = Array(	0 => 'No',
											1 => 'Yes' );
					$thisActivated = $activatedMsg[$thisActivated];
					$transformData = "
					<div class='resultData'>$thisQuotaUsed / $thisQuota</div>
						<div class='resultData' style='width:94px'>
							<a class='buttonlink' href='edit_reader.php?id=$thisCardId'>EDIT</a>
							<a class='buttonlink' href='delete_reader.php?id=$thisCardId'>DELETE</a>
						</div>
					";
				}

				$tableBody .= "
					<div class='resultRow'>
						<div class='resultData'>$thisCardId</div>
						<div class='resultDataL'>$thisGivenName</div>
						<div class='resultDataL'>$thisSurname</div>
						<div class='resultData'>$thisRegister_date</div>
						<div class='resultData'>$thisTel</div>
						<div class='resultData'>$thisIdCert</div>
						<div class='resultData'>$thisClass</div>
						<div class='resultData'>$thisActivated</div>
						$transformData
					</div>
				";
			}
			$tableBody .= '</div>';
		} else {
			$tableBody .= '</div><p class="warning">** No Table!</p>';
		}
		return $tableHeader.$tableBody;
	}

}

?>