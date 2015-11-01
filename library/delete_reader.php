<?php
	require './system_variable.php';  // initialize global uses constrain,
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
		<div id="page-header">Remove reader</div>
		<div id="sidebar">
			<span class="msgbox">
				<span class="tips"><span style="color: #fc661c">Tips!</span><br/>You may remove or delete a record here.</span>
			</span>
		</div>
		<p>In this section, you can virtually remove a reader by setting it card's status to "removed".<br/>
			If you want to premanently delete the record from the database, please check the box as prompted.</p>
		<hr/>

<?php

	if ( isset( $_POST['submit'] ) ){
		if ( isset( $_POST['id'] ) ){
			require_once $GLOBALS["classRoot"].'LibraryDb.php';
			$myLibrary = new LibraryDb();
			if ( $myLibrary->connect() )  {
				require_once $GLOBALS["classRoot"].'Readers.php';
				$thisReader = new Reader();
				$thisReader->setCardId( $_POST['id'] );
				if ( $_POST['submit'] == 'Confirm Remove' ){
					$execution = $myLibrary->removeRec($thisReader->getTableName(), 'activated', $thisReader->getIdName(), $thisReader->getCardId());
					if ( $execution ){
						$GLOBAL['debugMsg'] .= 'Record removed successfully.';
					} else {
						$GLOBAL['debugMsg'] .= 'Error occurs when trying to remove the record.';
					}
				} elseif ( $_POST['submit'] == 'Confirm Delete' ){
					$execution = $myLibrary->deleteRec($thisReader->getTableName(), $thisReader->getIdName(), $thisReader->getCardId());
					if ( $execution ){
						$GLOBAL['debugMsg'] .= 'Record permanently deleted from database successfully.';
					} else {
						$GLOBAL['debugMsg'] .= 'Error occurs when trying to delete the record.';
					}
				}
			}
		}
	} elseif ( isset( $_GET['id'] ) ){
		// print the reader record relevant to the book id.
		require_once $GLOBALS["classRoot"].'LibraryDb.php';
		$myLibrary = new LibraryDb();
		if (  $myLibrary->connect() )  {
			require_once $GLOBALS["classRoot"].'Readers.php';
			$thisReader = new Reader();
			$thisReader->setCardId( $_GET[id] );
			$record = $myLibrary->getRecords($thisReader->getTableName(), $thisReader->getIdName(), $thisReader->getCardId() );
			$searchResult = $thisReader->getTable( $record );
			if ( $searchResult != null ){
				echo $searchResult.'
				<form method="post" action="'.$_SERVER["PHP__SELF"].'">
			  	<table align="center">
			    	<tr><td><input type="radio" id="btnRemove" name="act" value="remove" checked>Remove <span style="color:#33F">* The reader will be marked as \'removed\' while the data is still keeping on the database.</span></td></tr>
			    	<tr><td><input type="radio" id="btnDelete" name="act" value="remove">Delete <span style="color:#F00">*Warning! The reader record will be permanently deleted from the database and is non-revertible.</span></td></tr>
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