<?php
	require 'system_variable.php';  // initialize global uses constrain,
	require $GLOBALS["classRoot"].'login.php';
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
	<div id="page-content">
		<div id="page-header">Remove book</div>
		<div id="sidebar">
			<span class="msgbox">
				<span class="tips"><span style="color: #fc661c">Tips!</span><br/>You may remove or delete a record here.</span>
			</span>
		</div>
		<p>In this section, you can virtually remove a book by setting its status to "removed".<br/>
			If you want to premanently delete the record from the database, please check the box as prompted.</p>
		<hr/>

<?php
	if ( isset( $_POST['submit'] ) ){
		if ( isset( $_POST['id'] ) ){
			require_once $GLOBALS["root"].'php_class/LibraryDb.php';
			$myLibrary = new LibraryDb();
			if ( $myLibrary->connect() )  {
				require_once $GLOBALS["root"].'php_class/Books.php';
				$thisBook = new Book();
				$thisBook->setBookId( $_POST['id'] );
				if ( $_POST['submit'] == 'Confirm Remove' ){
					$execution = $myLibrary->removeRec($thisBook->getTableName(), 'status', $thisBook->getIdName(), $thisBook->getBookId());
					if ( $execution ){
						$GLOBAL['debugMsg'] .= 'Record removed successfully.';
					} else {						$GLOBAL['debugMsg'] .= 'Error occurs when trying to remove the record.';
					}
				} elseif ( $_POST['submit'] == 'Confirm Delete' ){
					$execution = $myLibrary->deleteRec($thisBook->getTableName(), $thisBook->getIdName(), $thisBook->getBookId());
					if ( $execution ){
						$GLOBAL['debugMsg'] .= 'Record permanently deleted from database successfully.';
					} else {
						$GLOBAL['debugMsg'] .= 'Error occurs when trying to delete the record.';
					}
				}
			}
		}
	} elseif ( isset( $_GET['id'] ) ){		// print the book record relevant to the book id.
		require_once $GLOBALS["classRoot"].'LibraryDb.php';
		$myLibrary = new LibraryDb();
		if (  $myLibrary->connect() )  {
			require_once $GLOBALS["classRoot"].'Books.php';
			$thisBook = new Book();
			$thisBook->setBookId( $_GET[id] );
			$record = $myLibrary->getRecords($thisBook->getTableName(), $thisBook->getIdName(), $thisBook->getBookId() );
			$searchResult = $thisBook->getTable( $record );
			if ( $searchResult != null ){
				echo $searchResult.'
				<form method="post" action="'.$_SERVER["PHP__SELF"].'">
			  	<table align="center">
			    	<tr><td><input type="radio" id="btnRemove" name="act" value="remove" checked>Remove <span style="color:#33F">* The book will be marked as \'removed\' while the data is still keeping on the database.</span></td></tr>
			    	<tr><td><input type="radio" id="btnDelete" name="act" value="remove">Delete <span style="color:#F00">*Warning! The book record will be permanently deleted from the database and is non-revertible.</span></td></tr>
			    	<tr><td><input type="hidden" name="id" value="'.$_GET[id].'">
			      	<input type="submit" id="btnSubmit" name="submit" value="Confirm Remove"></td></tr>
				</form>
				';
			}
		}
	}

	echo '<div id="sysMsg">'.$GLOBAL['debugMsg'].'</div>';
?>
	</div>
</div>
</body>

</html>