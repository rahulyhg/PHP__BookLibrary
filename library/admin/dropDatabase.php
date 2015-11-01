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
<div class="header">Database Management - Drop the database</div>
<div class="subheader">Warning! This action will clean up all existing data!</div>
<?php
	require $GLOBALS["classRoot"].'databasetopmenu.php';
	echo databaseTopMenu();
?>

<?php

	require $GLOBALS["classRoot"].'LibraryDb.php';
	$myLibrary = new LibraryDb();

	if ( $_POST["confirm_drop"] == "on" )  {
		if ( $myLibrary->connect() )  {
			if ( $myLibrary->drop() ){
				echo "<p>Database '".$myLibrary->getDbName()."' dropped.</p>";
			} else {
				echo "<p>Database '".$myLibrary->getDbName()."' cannot be dropped.</p>";
			}
		} else {
			$sql = "<p>Database '".$myLibrary->getDbName()."' does not exist. Nothing to drop.</p>";
		}
	} else {
		echo "<form method='post' action='".$_SERVER['PHP_SELF']."'><p><input type='checkbox' name='confirm_drop'>Please check this box and click the below 'Confirm' button if you confirm to drop the database.</p>
	        <p class='warning'>Warning! The database will no longer exist once you make this action!</p>
	        <p><input type='submit' value='Confirm'></p>
			</form>";
	}

?>

</body>
</html>