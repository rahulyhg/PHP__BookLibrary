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
	<script>
		$(document).ready(function(){
			var formReaderId = $('#formReaderId').val();
			var formBookId = $('#formBookId').val();
			$.get("search_reader_name_by_id.php?id="+formReaderId, function( data ){
				if ( data.length > 0 ){
					$('#readerName').html( data );
				} else {
					$('#readerName').html( '' );
				}
			});

			$.get("search_book_name_by_id.php?id="+formBookId, function( data ){
				if ( data.length > 0 ){
					$('#bookName').html( data );
				} else {
					$('#bookName').html( '' );
				}
			});

			$('#formReaderId').keyup(function(){
				var formReaderId = $('#formReaderId').val();
				$.get("search_reader_name_by_id.php?id="+formReaderId, function( data ){
					if ( data.length > 0 ){
						$('#readerName').html( data );
					} else {
						$('#readerName').html( '' );
					}
				});
			});

			$('#formBookId').keyup(function(){
				var formBookId = $('#formBookId').val();
				$.get("search_book_name_by_id.php?id="+formBookId, function( data ){
					if ( data.length > 0 ){
						$('#bookName').html( data );
					} else {						$('#bookName').html( '' );
					}
				});
			});

		});
	</script>
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
	require_once $GLOBALS["root"].'php_class/Loans.php';


	if ( isset( $_GET['id'] ) )  {
		$myId = $_GET['id'];
	} elseif ( isset( $_POST['id'] ) )  {
		$myId = $_POST['id'];
	}

	if ( $myId > 0 ){
		if ( $myLibrary->connect() )  {
			if ( isset( $_POST['save_update'] ) )  {
				$thisLoan = new Loan(
								$_POST['id'],
								$_POST['transaction_date'],
								$_POST['reader_id'],
								$_POST['book_id'],
								$_POST['status']
							);
				$result = $myLibrary->updateRec($thisLoan->getTableName(), $thisLoan->newRec());
				if ( $result ){
					$debugMsg .= '<span style="color:#FFF">Record of loan ID #'.$thisLoan->getLoanId().' updated.</span>';
				} else {
					$debugMsg .= "<span style='color:#F00'>Error when updating the data. Please contact your webmaster.</span>";
				}
			}
			printFormWithData($myId);
		}
	}
	echo '<div id="sysMsg">'.$debugMsg.'</div>';

	function printFormWithData($myId){		global $myLibrary;
		$thisLoan = new Loan();
		$row = Array();
		$row = $myLibrary->getRowById($thisLoan->getTableName(), $thisLoan->getIdName(), $myId);
		$thisLoan->loadData( $row );

		echo '
		<form method="post" action="edit_loan.php">
		  <p><span class="formTitle">Transaction Date<span style="color: #FF0000">*</span></span>
		     <span class="formInput"><input type="text" name="transaction_date" value="'.$thisLoan->getTransactionDate().'"></span></p>
		  <p><span class="formTitle">Due Date<span style="color: #FF0000">*</span></span>
		     <span class="formInput"><input type="text" name="due_date" value="'.$thisLoan->getDueDate().'"></span></p>
		  <p><span class="formTitle">Reader Card ID<span style="color: #FF0000">*</span></span>
		     <span class="formInput"><input id="formReaderId" type="text" name="reader_id" value="'.$thisLoan->getCardId().'" size="8"> <span id="readerName" style="color:#449944"></span></span></p>
		  <p><span class="formTitle">Book ID<span style="color: #FF0000">*</span></span>
		     <span class="formInput"><input id="formBookId" type="text" name="book_id" value="'.$thisLoan->getBookId().'" size="8"> <span id="bookName" style="color:#449944"></span></span></p>
		  <p><span class="formTitle">Status<span style="color: #FF0000">*</span></span>
		     <span class="formInput"><input type="text" name="status" value="'.$thisLoan->getStatus().'"></span></p>
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