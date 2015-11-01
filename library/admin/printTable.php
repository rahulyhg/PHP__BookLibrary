<?php
  require '../system_variable.php';
  require $GLOBALS["classRoot"].'login.php';
  $logAs = checkLogin(); // from class login
?>
<html>
<head>
<title>Lam Kwok Shing's Library System</title>
<link rel="stylesheet" type="text/css" href="../css/admin_setting.css" />
</head>
<body>
<div class="header">Database Management - List Records from a table...</div>
<?php
  require $GLOBALS["classRoot"].'databasetopmenu.php';
  echo databaseTopMenu();
?>

<?php
	require $GLOBALS["classRoot"].'LibraryDb.php';
	$myLibrary = new LibraryDb();
	if ( isset( $_POST['tablename'] ) ){
		if ( $myLibrary->connect() )  {  // behavoir from connectMysqlServer.php
			printTable( $_POST["tablename"] );
		}
	} else {
		printEnquiryForm();
	}

	function printTable($tableName)  {
		global $myLibrary;
		require $GLOBALS["classRoot"].$tableName.'.php';
		switch( $tableName ){
			case 'UserClasses':
				$myTable = new UserClass($tableName);
				break;
			case 'Readers':
				$myTable = new Reader($tableName);
				break;
			case 'Books':
				$myTable = new Book($tableName);
				break;
			case 'Loans':
				$myTable = new Loan($tableName);
				break;
			default:;
		}
		$records = Array();
		$records = $myLibrary->getRecords($tableName);
		echo $myTable->getTable($records);
	}

	function printEnquiryForm()  {
		echo "
		<form method='post' action='".$_SERVER['PHP_SELF']."'>
		  <p>Please select table:
	        <select name='tablename'>
    	 	  <option>UserClasses</option>
		      <option>Readers</option>
		      <option>Books</option>
		      <option>Loans</option>
    	    </select>
	      </p>
    	  <p><input type='submit' value='Submit'></p>
	    </form>
    	";
	}

?>

<?php echo "<p class='warning'>".$GLOBALS['debugMsg']."</p>"; ?>

</body>
</html>