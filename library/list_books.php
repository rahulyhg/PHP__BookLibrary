<?php
  require 'system_variable.php';  // initialize global uses constrain,
  require $GLOBALS["root"].'php_class/login.php';
  $logAs = checkLogin(); // from class login
?>
<!DOCTYPE html>
<html>
<head>
	<title>Library</title>
	<!-- enable JQuery -->
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="./defaultjs.js"></script>
	<link rel="stylesheet" type="text/css" href="./css/default_setting.css" />
</head>

<body>

<div id="header">
	<div id="siteTitleBar">
		<span id="siteIcon"></span>
		<?php echo $GLOBALS['scriptTitle'] ?>
	</div>
	<div id="menuControlBar">
		<?php
			require $GLOBALS["classRoot"].'mainmenu.php';
			mainmenu( $logAs );  // mainmenu() from class mainmenu
		?>
	</div>
</div>


<div id="content">
	<div id="page-title"><a href="list_transactions.php">Transactions</a></div>
	<div id="page-title"><a href="list_readers.php">Readers</a></div>
	<div id="page-title-active">Books</div>
	<div id="page-content">
		<div id="page-header">List all books</div>
		<div id="sidebar">
			<span class="msgbox">
				<span style="color: #fc661c">Tips!</span><br/>This section will list all records.
			</span>
		</div>
		<p>In this section, you can list all books, edit a record, or delete a record.</p>

		<hr/>
		<!-- following lines show the search result -->
<?php

	require_once $GLOBALS["classRoot"].'LibraryDb.php';
	$myLibrary = new LibraryDb();
	if ( $myLibrary->connect() )  {
		require_once $GLOBALS["classRoot"].'Books.php';
		$myTable = new Book();
		$records = Array();
		$compile = true;
		$records = $myLibrary->getRecords($myTable->getTableName());
		echo $myTable->getTable($records,$compile);
	}

	echo '<div>'.$GLOBALS['debugMsg'].'</div>';

?>
	</div>
</div>
</body>

</html>