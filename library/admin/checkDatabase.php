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
<div class="header">Database Management - Check Database and make it ready to use...</div>
<?php
	require $GLOBALS["classRoot"].'databasetopmenu.php';
	echo databaseTopMenu();
?>


<?php

	require $GLOBALS["classRoot"].'LibraryDb.php';
	$myLibrary = new LibraryDb();

	echo "<p><span style='display: block; float: left; text-align: center; width: 160px; line-height: 22pt; background: #FFFF00; color: #FF00FF'>mySQL Server Details:</span><span style='display: block; float: clear; visibility: hidden'>.</span><br/>";
	echo "<span style='display: block; float: left; width: 70px'>Host</span>: ".$myLibrary->getDbHost()."<br/>";
	echo "<span style='display: block; float: left; width: 70px'>Login</span>: ".$myLibrary->getDbLogin()."<br/>";
	echo "<span style='display: block; float: left; width: 70px'>Password</span>: ".$myLibrary->getDbPassword()."</p>";
	if ( $myLibrary->connect() ){
		echo "<p>Database checked OK for use.</p>";
	} else {
		if ( $_POST['CreateDB'] == "on" ){
			if ( $myLibrary->create() ){
				echo "<p>Database '".$myLibrary->getDbName()."' created.</p>";
				echo "<p>Next step:<br/>Please add the require tables to the database by clicking the below button:<br/>
				<a href='addTable.php' class='buttonlink'>Add Tables</a></p>";
			} else {
				echo "<p>Database '".$myLibrary->getDbName()."' cannot be created.</p>";
			}
		} else {
			echo "<p style='color: #FF00FF'>Database name to create: ".$myLibrary->getDbName()."</p>";
			echo "<p class='warning'>Warning! Please check the above information is correct or you may need to call the webmaster for assistance!</p>";
			echo "<form method='post' action='".$_SERVER['PHP_SELF']."'><p><input type='checkbox' name='CreateDB'>Please check this box and click the below 'Confirm' button if you confirm to create the database.</p>
    	    <p><input type='submit' value='Confirm'></p>
			</form>";
		}
	}

?>

</body>
</html>