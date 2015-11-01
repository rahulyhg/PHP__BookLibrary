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
<div class="header">Database Management - drop tables ...</div>
<?php
  require $GLOBALS["classRoot"].'databasetopmenu.php';
  echo databaseTopMenu();
?>

<?php
	require $GLOBALS["classRoot"].'LibraryDb.php';
	$myLibrary = new LibraryDb();
	if ( $myLibrary->connect() )  {  // behavoir from connectMysqlServer.php
    // below list all major works in this page.
	dropTable('Loans');
	dropTable('Books');
	dropTable('Readers');
	dropTable('UserClasses');
  }

function dropTable( $tableName )  {
	global $myLibrary;
	if ( $myLibrary->isTableExist( $tableName ) ){
		if ( $myLibrary->dropTable( $tableName ) ){
			echo '<p style="color:#484">'.$tableName.' table dropped.</p>';
		} else {
			echo '<p style="color:#F00">'.$tableName.' table cannot be dropped. Please check your database.</p>';
		}
	} else {
		echo '<p style="color:#F0F">'.$tableName.' table already not exist.</p>';
	}
}

?>

<?php echo "<p class='warning'>".$GLOBALS['debugMsg']."</p>"; ?>

</body>
</html>