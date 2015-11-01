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
<div class="header">Database Management - Create tables for the system...</div>
<?php
  require $GLOBALS["classRoot"].'databasetopmenu.php';
  echo databaseTopMenu();
?>

<?php
	require $GLOBALS["classRoot"].'LibraryDb.php';
	$myLibrary = New LibraryDb();

	if ( $myLibrary->connect() )  {
		// below list all major works in this page.
		createTable('UserClasses');
		createTable('Readers');
		createTable('Books');
		createTable('Loans');
	}


	function createTable($thisTableName){
		global $myLibrary;
		require $GLOBALS["classRoot"].$thisTableName.'.php';
		switch($thisTableName){
			case 'UserClasses':
				$myTable = new UserClass();
				break;
			case 'Readers':
				$myTable = new Reader();
				break;
			case 'Books':
				$myTable = new Book();
				break;
			case 'Loans':
				$myTable = new Loan();
				break;
			default:;
		}
		if ( $myLibrary->isTableExist($thisTableName) ){
			$GLOBALS['debugMsg'] .= "<p>Table for $thisTableName already exist!</p>";
		} else {
			if ( $myLibrary->createTable($myTable->getTableName(), $myTable->getTableStructure()) )  {
				$GLOBALS['debugMsg'] .= "<p>Table for $thisTableName created!</p>";
			} else {
				$GLOBALS['debugMsg'] .= "<p>Error occurs when creating table for $thisTableName!</p>";
			}
		}
	}
?>

<?php echo "<p class='warning'>".$GLOBALS['debugMsg']."</p>"; ?>

</body>
</html>