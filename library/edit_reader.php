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
		<div id="page-header">Edit loan transaction record</div>
		<div id="sidebar">
			<span class="msgbox">
				<span class="tips"><span style="color: #fc661c">Tips!</span><br/>Fields marked with * cannot be empty.</span>
			</span>
		</div>
		<p>In this section, you can update loan transaction record.</p>
<?php
	require_once $GLOBALS["root"].'php_class/LibraryDb.php';
	$myLibrary = new LibraryDb();
	require_once $GLOBALS["root"].'php_class/Readers.php';


	if ( isset( $_GET['id'] ) )  {
		$myId = $_GET['id'];
	} elseif ( isset( $_POST['id'] ) )  {
		$myId = $_POST['id'];
	}

	if ( $myId > 0 ){
		if ( $myLibrary->connect() )  {
			if ( isset( $_POST['save_update'] ) )  {
				$thisReader = new Reader(
								$_POST['id'],
								$_POST['given_name'],
								$_POST['surname'],
								$_POST['register_date'],
								$_POST['tel'],
								$_POST['id_cert'],
								$_POST['class_id'],
								$_POST['activated']
							);
				$result = $myLibrary->updateRec($thisReader->getTableName(), $thisReader->newRec());
				if ( $result ){
					$debugMsg .= '<span style="color:#FFF">Record of card ID #'.$thisReader->getCardId().' updated.</span>';
				} else {
					$debugMsg .= "<span style='color:#F00'>Error when updating the data. Please contact your webmaster.</span>";
				}
			}
			printFormWithData($myId);
		}
	}
	echo '<div id="sysMsg">'.$debugMsg.'</div>';

	function printFormWithData($myId){		global $myLibrary;
		$thisReader = new Reader();
		$row = Array();
		$row = $myLibrary->getRowById($thisReader->getTableName(), $thisReader->getIdName(), $myId);
		$thisReader->loadData( $row );

		$classList = $myLibrary->getRecords('UserClasses');
		foreach( $classList as $thisClass ){			if ( $thisClass['class_id'] == $thisReader->getClassId() ){				$htmlClassOption .= '<option value="'.$thisClass['class_id'].'" selected>'.$thisClass['name'].'</option>'."\n";
			} else {				$htmlClassOption .= '<option value="'.$thisClass['class_id'].'">'.$thisClass['name'].'</option>'."\n";
			}		}

		echo '
		<form method="post" action="edit_reader.php">
			<p><span class="formTitle">Surname<span style="color: #FF0000">*</span></span>
				<span class="formInput"><input type="text" name="surname" value="'.$thisReader->getSurname().'"></span></p>
			<p><span class="formTitle">Given Name<span style="color: #FF0000">*</span></span>
				<span class="formInput"><input type="text" name="given_name" value="'.$thisReader->getGivenName().'"></span></p>
			<p><span class="formTitle">Register Date<span style="color: #FF0000">*</span></span>
				<span class="formInput"><input type="text" name="register_date" value="'.$thisReader->getRegisterDate().'"></span></p>
			<p><span class="formTitle">Tel<span style="color: #FF0000">*</span></span>
				<span class="formInput"><input type="text" name="tel" value="'.$thisReader->getTel().'"></span></p>
			<p><span class="formTitle">Id Cert No.<span style="color: #FF0000">*</span></span>
				<span class="formInput"><input type="text" name="id_cert" value="'.$thisReader->getIdCert().'"></span></p>
			<p><span class="formTitle">Activated<span style="color: #FF0000">*</span></span>
				<span class="formInput"><input type="text" name="activated" value="'.$thisReader->getActivated().'"></span></p>
			<p><span class="formTitle">Class<span style="color: #FF0000">*</span></span>
				<span class="formInput"><select name="class_id">'.$htmlClassOption.'</select></span></p>
			<p><input type="hidden" name="id" value="'.$myId.'" />
				<input type="submit" name="save_update" value="Update Record"></p>
		</form>
		';
	}
?>
	</div>
</div>
</body>

</html>