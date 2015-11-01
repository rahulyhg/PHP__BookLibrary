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
	<div id="page-content">
		<div id="page-header">Update current record</div>
		<div id="sidebar">
			<span class="msgbox">
				<span class="tips"><span style="color: #fc661c">Tips!</span><br/>Fields marked with * cannot be empty.</span>
			</span>
		</div>
		<p>In this section, you can update existing record.</p>

<?php
	require_once $GLOBALS["root"].'php_class/LibraryDb.php';
	$myLibrary = new LibraryDb();

	require_once $GLOBALS["root"].'php_class/Books.php';

	if ( isset( $_GET['id'] ) )  {		$myId = $_GET['id'];
	} elseif ( isset( $_POST['id'] ) )  {
		$myId = $_POST['id'];
	}

	if ( $myId > 0 ){		if ( $myLibrary->connect() )  {
			if ( isset( $_POST['save_update'] ) )  {
				$thisBook = new Book(
								$_POST['id'],
								$_POST['name'],
								$_POST['edition'],
								$_POST['author'],
								$_POST['publisher'],
								$_POST['year'],
								$_POST['status']
							);
				$result = $myLibrary->updateRec($thisBook->getTableName(), $thisBook->newRec());
				if ( $result ){					$debugMsg .= '<span style="color:#FFF">Record of book ID #'.$thisBook->getBookId().' updated.</span>';				} else {					$debugMsg .= "<span style='color:#F00'>Error when updating the data. Please contact your webmaster.</span>";
				}
			}
			printFormWithData($myId);
		}
	}
	echo '<div id="sysMsg">'.$debugMsg.'</div>';

	function printFormWithData($myId){		global $myLibrary;		$thisBook = new Book();
		$row = Array();
		$row = $myLibrary->getRowById($thisBook->getTableName(), $thisBook->getIdName(), $myId);
		$thisBook->loadData( $row );		$htmlStatusMenu = $thisBook->getStatusSelectMenu($thisBook->getStatus());
		echo '
<form method="post" action="edit_book.php">
  <p><span class="formTitle">Book Name<span style="color: #FF0000">*</span></span>
     <span class="formInput"><input type="text" name="name" value="'.$thisBook->getName().'" size="80"/></span></p>
  <p><span class="formTitle">Edition<span style="color: #FF0000">*</span></span>
     <span class="formInput"><input type="number" name="edition" value="'.$thisBook->getEdition().'" min="1" size="2" value="1"/></span></p>
  <p><span class="formTitle">Author<span style="color: #FF0000">*</span></span>
     <span class="formInput"><input type="text" name="author" value="'.$thisBook->getAuthor().'" size="80"/></span></p>
  <p><span class="formTitle">Publisher<span style="color: #FF0000">*</span></span>
     <span class="formInput"><input type="text" name="publisher" value="'.$thisBook->getPublisher().'" size="80"/></span></p>
  <p><span class="formTitle">Publish Year<span style="color: #FF0000">*</span></span>
     <span class="formInput"><input type="number" name="year" value="'.$thisBook->getYear().'" min="1800" size="4"/></span></p>
  <p><span class="formTitle">Status<span style="color: #FF0000">*</span></span>
     <span class="formInput">'.$htmlStatusMenu.'</span></p>
  <p><input type="hidden" name="id" value="'.$myId.'" />
     <input type="submit" name="save_update" value="Update Record"/></div>
</form>
		';
	}
?>
	</div>
</div>
</body>

</html>