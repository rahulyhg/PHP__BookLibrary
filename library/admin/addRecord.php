<?php
  require '../system_variable.php';
  require $GLOBALS["classRoot"].'login.php';
  $logAs = checkLogin(); // from class login
?>
<head>
<title>Lam Kwok Shing's Library System</title>
<link rel="stylesheet" type="text/css" href="../css/admin_setting.css" />
</head>
<body>
<div class="header">Database Management - Add new record into table...</div>
<?php
  require $GLOBALS["classRoot"].'databasetopmenu.php';
  echo databaseTopMenu();
?>

<?php

require $GLOBALS["classRoot"].'mysqlConnectInfo.php';
require $GLOBALS["classRoot"].'connectMysqlServer.php';

// Below shows the logical flow of this program.
if ( $_POST["tablename"] )  {
	connectMysqlServer( mysqlConnectInfo() );
	if ( checkDatabase( mysqlConnectInfo_getDBName() ) )  {
		if ( mysql_Select_DB( mysqlConnectInfo_getDBName() ) )  {
			switch ( $_POST["tablename"] )  {
				case 'Classes':
					addClasses();
					break;
				case 'Readers':
					addReaders();
					break;
				case 'Books':
					addBooks();
					break;
				case 'Loans':
					addLoans();
					break;
				case 'Stacks':
					addStacks();
					break;
				default:
					echo "<p>Unknown table name.</p>";
					break;
			}
		} else {
			echo "<p>Fail to use database '".mysqlConnectInfo_getDBName()."'.</p>";
		}
	} else {
		echo "<p>Please contact your webmaster to solve the problem.</p>";
	}
} else {
	printEnquiryForm();
}

// Create a table store the user's class details.
function addClasses()  {
	require $GLOBALS["classRoot"]."Classes.php";
	addClassesRecords();
}

// Create a table store the reader's information of each library card.
function addReaders()  {
	require $GLOBALS["classRoot"]."Readers.php";
	addReadersRecords();
}

// Create a table store the book's information of each book owned by the library.
function addBooks()  {
	require $GLOBALS["classRoot"]."Books.php";
	addBooksRecords();
}

// Create a table store the loans information of each loan record.
function addLoans()  {
	require $GLOBALS["classRoot"]."Loans.php";
	addLoansRecords();
}

// Create a table stores the stack to let user to make booking if the book is being loaned.
function addStacks()  {
	require $GLOBALS["classRoot"]."Stacks.php";
	addStacksRecords();
}

//
function printEnquiryForm()  {
	echo "<form method='post' action='".$_SERVER['PHP_SELF']."'><p>Please select table:
<select name='tablename'>
	<option>Classes</option>
	<option>Readers</option>
	<option>Books</option>
	<option>Loans</option>
	<option>Stacks</option>
	<option>Test</option>
</select></p>
<p><input type='submit' value='Submit'></p>
</form>
"
;
}

?>

</body>
