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
	<div id="page-subtitle"><a href="new_transaction.php">Borrow</a></div>
	<div id="page-subtitle"><a href="return_book.php">Return</a></div>
	<div id="page-title-active">Add Reader</div>
	<div id="page-title"><a href="new_book.php">Add Book</a></div>
	<div id="page-content">
		<div id="page-header">Create new reader record</div>
		<div id="sidebar">
			<span class="msgbox">
				<span class="tips"><span style="color: #fc661c">Tips!</span><br/>No duplucate ID cert No. (maybe student ID or HKID card no., depends on the library issue) can be stored into the database. It means one reader can only have one library card.</span>
			</span>
		</div>
		<p>In this section, you can add new reader record into the database.</p>

<?php

	require $GLOBALS["classRoot"].'LibraryDb.php';
	$myLibrary = new LibraryDb();

	if ( isset( $_POST['submit'] ) )  {
		require_once $GLOBALS["classRoot"].'Readers.php';
		$newReader = new Reader();

		if ( $myLibrary->connect() )  {
			date_default_timezone_set( 'Asia/Hong_Kong' );
			$thisDate =  date("Y-m-d");

			$newReader->setGivenName( $_POST['givenname'] );
			$newReader->setSurname( $_POST['surname'] );
			$newReader->setRegisterDate( $thisDate );
			$newReader->setTel( $_POST['tel'] );
			$newReader->setIdCert( $_POST['idcert'] );
			$newReader->setClassId( $_POST['class'] );
			$newReader->setActivated( $_POST['activate'] );

			// check if this id cert have already been issued a library card.
			$existReader = $myLibrary->getRowById( $newReader->getTableName(), 'id_cert', $newReader->getIdCert() );
			if ($existReader){				echo '<p style="color:#FF0000">This ID cert no. is already used by card #'.$existReader['card_id'].'</p>';
				echo '<p>Do you want to edit the reader record?</p>
					<p style="clear:both;"><a class="buttonlink" style="margin:0.35em; font-size:0.875em;" href="edit_reader.php?id='.$existReader['card_id'].'">Yes. Please let me edit the record.</a></p>
					<p style="clear:both;"><a class="buttonlink" style="margin:0.35em; font-size:0.875em;" href="new_reader.php">No. Please return to create new reader form.</a></p>';
			}
			else {
				$execution = $myLibrary->addRec($newReader->getTableName(), '',
							array(
								$newReader->getGivenName(),
								$newReader->getSurname(),
								$newReader->getRegisterDate(),
								$newReader->getTel(),
								$newReader->getIdCert(),
								$newReader->getClassId(),
								$newReader->getActivated()
    						) );

				if ( $execution ){
					echo '
					<p>Card for reader '.$newReader->getGivenName().' '.$newReader->getSurname().' created.</p>
					<form metho="post"><input type="submit" value="Add more record"></form>
					';
				} else {
					echo "<p style='color:#FF0000'>Error when saving the data. Please contact your webmaster.</p>";
					printForm();
				}
			}
		}
	} else {
		printForm();
	}

	function printForm(){		global $myLibrary;

		if ( $myLibrary->connect() )  {  // behavoir from connectMysqlServer.php
			$tableName = 'UserClasses';
	 		require_once $GLOBALS["classRoot"].$tableName.'.php';
			$myClass = new UserClass($tableName);

			$classList = Array();
			$classList = $myLibrary->getFieldList( $tableName, $myClass->getIdName(), 'name' );
			foreach ( $classList as $thisClass )  {				$thisClassId = $thisClass[0];
				$thisClassName = $thisClass[1];
				$optionList .= "<option value='$thisClassId'>$thisClassName</option>\n";
			}
		}

		echo '
<form method="post">
  <p><span class="formTitle">Surname<span style="color: #FF0000">*</span></span>
     <span class="formInput"><input type="text" name="surname" size="50" required ></span></p>
  <p><span class="formTitle">Given Name<span style="color: #FF0000">*</span></span>
     <span class="formInput"><input type="text" name="givenname" size="50" required ></span></p>
  <p><span class="formTitle">Tel No.</span>
     <span class="formInput"><input type="text" name="tel" size="50"></span></p>
  <p><span class="formTitle">ID Cert. No.<span style="color: #FF0000">*</span></span>
     <span class="formInput"><input type="text" name="idcert" size="50" required ></span></p>
  <p><span class="formTitle">Activate<span style="color: #FF0000">*</span></span>
     <span class="formInput"><input type="radio" name="activate" value="1">Yes</span>
     <span class="formInput"><input type="radio" name="activate" value="0" checked>No</span></p>
  <p><span class="formTitle">Class<span style="color: #FF0000">*</span></span>
     <span class="formInput">
       <select name="class">'.$optionList.'</select>
  <p><input type="submit" name="submit" value="Add to database"></p>
</form>
';
	}

?>

	<?php echo "<p class='warning'>".$GLOBALS['debugMsg']."</p>"; ?>
	</div>
</div>
</body>

</html>