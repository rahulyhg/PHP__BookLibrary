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
<div class="header">Database Management - Clear tables ...</div>
<?php
  require $GLOBALS["classRoot"].'databasetopmenu.php';
  echo databaseTopMenu();
?>

<?php
	require $GLOBALS["classRoot"].'LibraryDb.php';
	$myLibrary = new LibraryDb();
	if ( $myLibrary->connect() )  {
		// below list all major works in this page.
		clearTable('Loans');
		clearTable('Books');
		clearTable('Readers');
		clearTable('UserClasses');
	}

	function clearTable($tableName)  {
		global $myLibrary;
		if ( $myLibrary->isTableExist($tableName) ){
			$noOfRec = $myLibrary->clearTable($tableName);
			echo '<p style="color:#484">Table \''.$myLibrary->getTableName($tableName).'\' cleared. '.$noOfRec.' record(s) removed.</p>';
		} else {
			echo '<p style="color:#F00">Table \''.$myLibrary->getTableName($tableName).'\' not exist. No record removed.</p>';
		}
	}

?>

<?php echo "<p class='warning'>".$GLOBALS['debugMsg']."</p>"; ?>

</body>
</html>