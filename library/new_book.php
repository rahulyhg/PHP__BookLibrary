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
	<div id="page-subtitle"><a href="new_transaction.php">Borrow</a></div>
	<div id="page-subtitle"><a href="return_book.php">Return</a></div>
	<div id="page-title"><a href="new_reader.php">Add Reader</a></div>
	<div id="page-title-active">Add Book</div>
	<div id="page-content">
		<div id="page-header">Create new book record</div>
		<div id="sidebar">
			<span class="msgbox">
				<span class="tips"><span style="color: #fc661c">Tips!</span><br/>Multiple books with the same information is allowed in this system which can be indentified by the book ID.</span>
			</span>
		</div>
		<p>In this section, you can add new book record into the database.</p>

<?php
	if ( isset( $_POST['submit'] ) )  {
		require_once $GLOBALS["classRoot"].'LibraryDb.php';
		$myLibrary = new LibraryDb();
		if (  $myLibrary->connect() )  {
			require_once $GLOBALS["classRoot"].'Books.php';
			$newBook = new Book();

			$newBook->setName( $_POST['name'] );
			$newBook->setEdition( $_POST['edition'] );
			$newBook->setAuthor( $_POST['author'] );
			$newBook->setPublisher( $_POST['publisher'] );
			$newBook->setYear( $_POST['year'] );
			$newBook->setStatus( 1 );

			$execution = $myLibrary->addRec($newBook->getTableName(), '',
							array(
								$newBook->getName(),
								$newBook->getEdition(),
								$newBook->getAuthor(),
								$newBook->getPublisher(),
								$newBook->getYear(),
								$newBook->getStatus()
							) );
			if ( $execution ){				echo '
				<p>Record added.<br/>'.$newBook->getName().' is now available for loan.</p>
				<form metho="post"><input type="submit" value="Add more record"></form>
				';			} else {				echo "<p style='color:#FF0000'>Error when saving the data. Please contact your webmaster.</p>";
				printForm();			}
		}
	} else {		printForm();
	}

	function printForm(){		echo '
		<form method="post" action="new_book.php">
		  <p><span class="formTitle">Book Name<span style="color: #FF0000">*</span></span>
		     <span class="formInput"><input type="text" name="name" size="80" required /></span></p>
		  <p><span class="formTitle">Edition<span style="color: #FF0000">*</span></span>
		     <span class="formInput"><input type="number" name="edition" min="1" size="2" value="1" required /></span></p>
		  <p><span class="formTitle">Author<span style="color: #FF0000">*</span></span>
		     <span class="formInput"><input type="text" name="author" size="80" required /></span></p>
		  <p><span class="formTitle">Publisher<span style="color: #FF0000">*</span></span>
		     <span class="formInput"><input type="text" name="publisher" size="80" required /></span></p>
		  <p><span class="formTitle">Publish Year<span style="color: #FF0000">*</span></span>
		     <span class="formInput"><input type="number" name="year" size="4" required /></span></p>
		  <p><input type="submit" name="submit" value="Add to database"/></div>
		</form>
		';	}
?>
	</div>
</div>
</body>

</html>