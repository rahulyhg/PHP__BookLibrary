<?php

class Book
{
	private $book_id;
	private $name;
	private $edition;
	private $author;
	private $publisher;
	private $year;
	private $status = Array('Removed','Available','On Loan');


	public function __construct(){
	}

	public function getBookId()   { return $this->book_id; }
	public function getName()     { return $this->name; }
	public function getEdition()  { return $this->edition; }
	public function getAuthor()   { return $this->author; }
	public function getPublisher(){ return $this->publisher; }
	public function getYear()     { return $this->year; }
	public function getStatus()   { return $this->status; }

	private function setValPosInt(&$var, $val){
		// set value with non-zero positive integer
		if ( $val > 0 ){
			$var = $val;
			$returnValue = 1;
		} else {
			$returnValue = 0;
		}
		return $returnValue;
	}

	public function setBookId($val)   {
	  // book id start from 1 and must be a non-zero positive integer.
	  $returnValue = $this->setValPosInt($this->book_id, $val);
	  return $returnValue;
	}

	public function setName($val)     { $this->name = $val; }

	public function setEdition($val)   {
	  // edition must be a non-zero positive integer.
	  $returnValue = $this->setValPosInt($this->edition, $val);
	  return $returnValue;
	}

	public function setAuthor($val)   { $this->author = $val; }
	public function setPublisher($val){ $this->publisher = $val; }
	public function setYear($val)     { $this->year = $val; }
	public function setStatus($val)   { $this->status = $val; }
}

class BookTable
{
    private $tableName;

	public function __construct(){
		$this->tableName = 'lib_Books';
	}

	public function getName()   { return $this->tableName; }
}

?>