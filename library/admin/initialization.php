<!--
This page is used for initialization for all tables in database.
Please ensure database 'library' is not exist when execute this file.
-->
<header>
<title>Lam Kwok Shing's Library System</title>
</header>
<body>
<h1>Initializing database and tables for the system...</h1>

<?php

// Below shows the logical flow of this program.
connectServer();
if ( createDatabase( 'library' ) )  {
   createTableClasses();
   createTableReaders();
   createTableBooks();
   createTableLoans();
   createTableStack();
   showNextStep();
}

// First, let's make a connection to the MySQL server.
function connectServer()  {
   if ( $con = mysql_connect( "localhost", "root", "admintoni" ) )  {
	    echo "<p>Connected to server.</p>";
	 }
}

// Create the database 'library'.
function createDatabase( $dbName )  {
	 if ( $result = mysql_query( "create database ".$dbName.";" ))  {
	    echo "<p>Database '".$dbName."' created!</p>";
			mysql_select_db( $dbName );
			$returnValue = 1;
	 } else {
		  echo "<p>Database '".$dbName."' cannot be created!</p>";
			$returnValue = 0;
	 }
	 return $returnValue;
}


// Create a table store the user's class details.
function createTableClasses()  {/* Field declaration:
   class_level					[PK] Store the level of class of the user. Start from class 0. This field should be in auto increment.
   name									Store the class name. (E.g. Master, Undergraduate, etc)
	 allowance						Shote the number of book allows to loan in this class.
*/
	 $sql = "create table Classes (
	 		class_level				int(3) auto_increment primary key,
			name							varchar(10),
			allowance					int(3)
			) engine innodb;";
	 if ( $result = mysql_query( $sql ))  {
	 		echo "<p>Table 'Classes' created!</p>";
	 } else {
	 		echo "<p>Table 'Classes' cannot be created!</p>";
	 }
}

// Create a table store the reader's information of each library card.
/* Field declaration:
	 card_id					 [PK] Store the library card number. This Field should be in auto increment.
	 last_name				 Store the last name of the card holder.
	 surname					 Store the surname of the card holder.
	 register_date		 Store the date of the card being registered to the card holder.
	 tel							 Store the contact phone number of the card holder.
	 id_cert					 Store the HKID card number / student ID number to verify the uniqueness of the user. (Only one library card can be activated with the same id_cert number.)
	 activated				 Store rather this card is activated or not. (TRUE = activated, FALSE = inactivated)
	 class						 [FK] Store the user level of this card. Classes details please refer to table 'Classes'.
*/
function createTableReaders()  {
	 $sql = "create table Readers (
	 		card_id	 			 		int(10) auto_increment primary key,
			last_name					varchar(30),
			surname						varchar(20),
			register_date			date,
			tel								varchar(16),
			id_cert						varchar(12),
			activated					boolean,
			class							int(3),
			foreign key (class) references Classes(class_level)
			) engine innodb;";

	 if ( $result = mysql_query( $sql ))  {
	 		echo "<p>Table 'Readers' created!</p>";
	 } else {
	 		echo "<p>Table 'Readers' cannot be created!</p>";
	 }
}

// Create a table store the book's information of each book owned by the library.
/* Field declaration:
	 book_id					 [PK] Store the book id of the book. This field should be in auto increment.
	 name							 Store the name of the book.
	 publisher				 Store the publisher's name.
	 author						 Store the author(s) name.
	 edition					 Store the number of edition of the book.
	 year							 Store the year of this book being published.
*/
function createTableBooks()  {
	 $sql = "create table Books (
	 		book_id						int(10) auto_increment primary key,
			name							varchar(256),
			publisher					varchar(100),
			author						varchar(256),
			edition						int(3),
			year							int(4)
	 ) engine innodb;";

	 if ( $result = mysql_query( $sql ))  {
	 		echo "<p>Table 'Books' created!</p>";
	 } else {
	 		echo "<p>Table 'Books' cannot be created!</p>";
	 }
}


// Create a table store the loans information of each loan record.
/* Field declaration:
	 loan_id					 [PK] Store the loan record id number. This field should be in auto increment and cannot delete.
	 borrower_id			 [FK] Store the library card number. It should be verified before record is inserted.
	 borrow_date			 Store the date of the book being borrowed.
	 due_date					 Store the due date of this loan record.
	 book_id					 [FK] Store the book id of the book being loan.
	 status						 Store the status of the loan¡CUse 2 abbr. letter to represent. ( E.g. "OL" = on loan; "VO" = void; "RT" = returned)
*/
function createTableLoans()  {
	 $sql = "create table Loans (
		 	loan_id	 			 		int(10) auto_increment primary key,
		 	borrower_id				int(10),
		 	borrow_date				date,
		 	due_date					date,
		 	book_id						int(10),
		 	status						varchar(2),
		 	foreign key (borrower_id) references Readers(card_id),
		 	foreign key (book_id) references Books(book_id)
			) engine innodb;";

	 if ( $result = mysql_query( $sql ))  {
	 		echo "<p>Table 'Loans' created!</p>";
	 } else {
	 		echo "<p>Table 'Loans' cannot be created!</p>";
	 }

}

// Create a table stores the stack to let user to make booking if the book is being loaned.
/* Field declaration:
	 stack_id					 [PK] Store the stack id. This field should be in auto increment.
	 stack_date				 Store the date when this stack is created.
	 borrower_id			 [FK] Store which one is waiting for the book.
	 book_id					 [FK] Store which book is being requested.
*/
function createTableStack()  {
	 $sql = "create table Stack (
	    stack_id					int(10)	auto_increment primary key,
			stack_date				date,
			borrower_id				int(10),
			book_id						int(10),
		  foreign key (borrower_id) references Readers(card_id),
		  foreign key (book_id) references Books(book_id)
			) engine innodb;";

	 if ( $result = mysql_query( $sql ))  {
	 		echo "<p>Table 'Stack' created!</p>";
	 } else {
	 		echo "<p>Table 'Stack' cannot be created!</p>";
	 }

}

function showNextStep()  {
   echo "<form method='post' action='set_default_value.php'><p><input type='Submit' value='Next Step'></p></form>";
}

?>

<p style="font-size: 10; color: #00C0C0">Tips: If you encountered an error in this stage, please check your MySQL server and ensure there is no databse called 'library' as this is the name of the database being use in this library system.</p>
</body>