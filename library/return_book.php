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
		var isBookExist = false;
		$(document).ready(function(){

			$('#formBookId').bind('input', function(){
				var formBookId = $('#formBookId').val();
				$.get("search_book_name_by_id.php?id="+formBookId, function( data ){
					if ( data.length > 0 ){
						$('#bookName').html( data );
						$('#formBookId').css({
							"background-color":"#DFD"
						});
						isBookExist = true;
					} else {						$('#bookName').html( '' );
						$('#formBookId').css({
							"background-color":"#FFF"
						});
						isBookExist = false;
					}
				});
			});

			$('#returnForm').submit(function(event){
				if ( isBookExist ){
					$(this).submit();
				} else {
					event.preventDefault();
					alert("Book ID not found!");
				}
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
	<div id="page-title"><a href="new_transaction.php">Borrow</a></div>
	<div id="page-title-active">Return</div>
	<div id="page-subtitle"><a href="new_reader.php">Add Reader</a></div>
	<div id="page-subtitle"><a href="new_book.php">Add Book</a></div>
	<div id="page-content">
		<div id="page-header">Return book</div>
		<div id="sidebar">
			<span class="msgbox">
				<span style="color: #fc661c">Tips!</span><br/>
				Only book that on loan can be returned.
			</span>
		</div>
		<p>In this section, you can set book status from borrowed back to available and free the reader's loan quota.</p>

<?php
	date_default_timezone_set( 'Asia/Hong_Kong' );
	$thisDate =  date("Y-m-d");

	if ( isset( $_POST['submit'] ) ){
		require_once $GLOBALS["classRoot"].'LibraryDb.php';
		$myLibrary = new LibraryDb();

		if ( $myLibrary->connect() ){
			$bookCurrentStatus = $myLibrary->getFieldDataById( 'Books', 'status', 'book_id', $_POST['book_id'] );

			if ( $bookCurrentStatus == 2 ){				// do the following if the book is being loan.

				$lastBorrowedReader = $myLibrary->getFieldDataById( 'Loans', 'card_id', 'book_id', $_POST['book_id'], 'loan_id', 'desc' );
				$lastLoanDueDate = $myLibrary->getFieldDataById( 'Loans', 'due_date', 'book_id', $_POST['book_id'], 'loan_id', 'desc' );
				$dueDuration = (strtotime($thisDate) - strtotime($lastLoanDueDate)) / 86400;
				if ($dueDuration > 0){					echo '
						<p style="color: #F5A">This loan is overdued where due date is '.$lastLoanDueDate.'. Please pay the penalty fee.<br />
						Penalty Fee <br />= $0.5 x no. of days('.$dueDuration.') <br />= $'.($dueDuration*0.5).'</p>
					';
				}



				$returnStatusCode = 2;
				require_once $GLOBALS["classRoot"].'Loans.php';
				$newLoan = new Loan(
							'',
							$thisDate,
							$lastBorrowedReader,
							$_POST['book_id'],
							$returnStatusCode );
				$execution = $myLibrary->addRec($newLoan->getTableName(), '',
								array(
								$newLoan->getTransactionDate(),
								$newLoan->getTransactionDate(),
								$newLoan->getCardId(),
								$newLoan->getBookId(),
								$newLoan->getStatus()
								) );

				if ( $execution ){
					// update book table and set the relavent book's status to (0)Available.
					$bookNewStatusQuery = ' status = 1 where book_id = '.$_POST['book_id'];
					$result = $myLibrary->updateRec('Books', $bookNewStatusQuery );
					echo '
						<p>Book returned successfully.</p>
						<p><form method="get" action="'.$_SERVER['PHP_SELF'].'?">
							<input type="submit" value="return another book"></form></p>
					';
				} else {
					echo '<p style="color:#FF0000">Error when saving the data. Please contact your webmaster.</p>';
				  	printForm($thisDate);
				}
			} else {				echo '<p style="color:#FF0000">The book is not being borrowed! Return process cancelled.</p>';
			  	printForm($thisDate);
			}
		} else {
			echo '<p style="color:#FF0000">Cannot connect to database.</p>';
			printForm($thisDate);
		}
	} else {
		printForm($thisDate);
	}

	function printForm($thisDate){
		echo '
		<form id="returnForm" method="post" action="'.$_SERVER['PHP_SELF'].'">
			<p><span class="formTitle">Transaction Date:</span>
				<span class="formInput">
				'.$thisDate.'
				</p>
			<p><span class="formTitle">Book ID<span style="color: #FF0000">*</span></span>
				<span class="formInput"><input id="formBookId" type="text" name="book_id" size="8"> <span id="bookName" style="color:#449944"></span></span></p>
			<p><input type="submit" name="submit" value="Confirm Return"></p>
		</form>
		';
	}
?>
	</div>
</div>

</body>

</html>