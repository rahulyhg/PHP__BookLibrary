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
		var isReaderExist = false;
		var isBookExist = false;

		$(document).ready(function(){
			$('#formReaderId').bind('input', function(){
				var formReaderId = $('#formReaderId').val();
				$.get("search_reader_name_by_id.php?id="+formReaderId, function( data ){
					if ( data.length > 0 ){
						$('#readerName').html( data );
						$('#formReaderId').css({
							"background-color":"#DFD"
						});
						isReaderExist = true;
					} else {
						$('#readerName').html( '' );
						$('#formReaderId').css({
							"background-color":"#FFF"
						});
						isReaderExist = false;
					}
				});
			});

			$('#formBookId').bind('input', function(){
				var formBookId = $('#formBookId').val();
				$.get("search_book_name_by_id.php?id="+formBookId, function( data ){
					if ( data.length > 0 ){
						$('#bookName').html( data );
						$('#formBookId').css({							"background-color":"#DFD"						});
						isBookExist = true;
					} else {						$('#bookName').html( '' );
						$('#formBookId').css({
							"background-color":"#FFF"
						});
						isBookExist = false;
					}
				});
			});

			$('#formNew').submit(function(event){				if ( isReaderExist && isBookExist ){					$(this).submit();
				} else {					event.preventDefault();					if ( !(isReaderExist || isBookExist) ){
						alert("Both Reader ID and Book Id not found!");
					} else if ( !isReaderExist ){						alert("Reader ID not found!");
					} else if ( !isBookExist ){
						alert("Book ID not found!");
					} else {						alert("Error");
					}
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
	<div id="page-title-active">Borrow</div>
	<div id="page-title"><a href="return_book.php">Return</a></div>
	<div id="page-subtitle"><a href="new_reader.php">Add Reader</a></div>
	<div id="page-subtitle"><a href="new_book.php">Add Book</a></div>
	<div id="page-content">
		<div id="page-header">Create new transaction record</div>
		<div id="sidebar">
			<span class="msgbox">
				<span style="color: #fc661c">Tips!</span><br/>
				If the correct ID is entered, the box color will change to green. If both box is changed to green, then you can press the 'Process Loan' button to go to next step.
			</span>
		</div>
		<p>In this section, you can add new transaction record into the database.</p>

<?php
	date_default_timezone_set( 'Asia/Hong_Kong' );
	$thisDate =  date("Y-m-d");

	if ( isset( $_POST['submit'] ) ){
		require_once $GLOBALS["classRoot"].'LibraryDb.php';
		$myLibrary = new LibraryDb();

		if ( $myLibrary->connect() ){
			$countReader = $myLibrary->countRecord('Readers', 'card_id', $_POST['reader_id'] );
			$countBook = $myLibrary->countRecord('Books', 'book_id', $_POST['book_id'] );
			if ( ( $countReader > 0 ) && ( $countBook > 0 ) ){				// check if the book is available for loan.				$bookCurrentStatus = $myLibrary->getFieldDataById( 'Books', 'status', 'book_id', $_POST['book_id'] );

				// check if the reader is activated.
				$readerCurrentStatus = $myLibrary->getFieldDataById( 'Readers', 'activated', 'card_id', $_POST['reader_id'] );
				if ( $readerCurrentStatus == 1 ) {					// check if the reader exceed it's loan limit.
					$thisClass = $myLibrary->getFieldDataById('Readers','class_id','card_id',$_POST['reader_id']);
					$thisQuota = $myLibrary->getFieldDataById('UserClasses','allowance','class_id',$thisClass);
					$thisReaderBorrowed = $myLibrary->countRecord('Loans','card_id',$_POST['reader_id'].'\' and status = \'1');
					$thisReaderReturned = $myLibrary->countRecord('Loans','card_id',$_POST['reader_id'].'\' and status = \'2');
					$thisQuotaUsed = $thisReaderBorrowed - $thisReaderReturned;
				}

				if (( $bookCurrentStatus == 1 ) && ( $readerCurrentStatus == 1 ) && ( $thisQuotaUsed<$thisQuota)){
					$myDefaultStatus = 1;					require_once $GLOBALS["classRoot"].'Loans.php';
					$newLoan = new Loan(
								'',
								$thisDate,
								$_POST['reader_id'],
								$_POST['book_id'],
								$myDefaultStatus );

					$execution = $myLibrary->addRec($newLoan->getTableName(), '',
								array(
									$newLoan->getTransactionDate(),
									$newLoan->getDueDate(),
									$newLoan->getCardId(),
									$newLoan->getBookId(),
									$newLoan->getStatus()
									) );

					if ( $execution ){						// update book table and set the relavent book's status to (2)Borrowed.
						$bookNewStatusQuery = ' status = 2 where book_id = '.$_POST['book_id'];
						$result = $myLibrary->updateRec('Books', $bookNewStatusQuery );
						echo '
							<p>Loan record saved. Please return the book on or before: '.$newLoan->getDueDate().'</p>
							<p><form method="get" action="'.$_SERVER['PHP_SELF'].'">
								<input type="hidden" name="cardId" value="'.$newLoan->getCardId().'">
								<input type="submit" value="Add more record for this reader (Card ID#'.$newLoan->getCardId().')"></form></p>
							<p><form method="get" action="'.$_SERVER['PHP_SELF'].'?">
								<input type="submit" value="Add record with new card id"></form></p>
						';
					} else {
						echo '<p style="color:#FF0000">Error when saving the data. Please contact your webmaster.</p>';
					  	printForm($thisDate);
					}
				} else {					echo '<p style="color:#FF0000">';					if ( $bookCurrentStatus != 1 ){						echo 'This book is currently unavailable!';
					}
					if ( $readerCurrentStatus != 1 ){
						echo 'This card is not activated!';
					} elseif ( $thisQuotaUsed >= $thisQuota ){
						echo 'This user has already used up his/her quota! '.$thisQuotaUsed.' out of '.$thisQuota.'.';
					}
					echo ' Loan cancelled.</p>';
				  	printForm($thisDate);
				}
			} else {
				echo '<p style="color:#FF0000">Unknown ';
				if ( $countReader == 0 ){ echo 'Card Id'; }
				if ( $countBook == 0 ){ echo 'Book Id'; }
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
		<form id="formNew" method="post" action="'.$_SERVER['PHP_SELF'].'">
			<p><span class="formTitle">Transaction Date:</span>
				<span class="formInput">
				'.$thisDate.'
				</p>
			<p><span class="formTitle">Reader Card ID<span style="color: #FF0000">*</span></span>
				<span class="formInput"><input id="formReaderId" type="text" name="reader_id" value="'.$_GET['cardId'].'" size="8"> <span id="readerName" style="color:#449944"></span></span></p>
			<p><span class="formTitle">Book ID<span style="color: #FF0000">*</span></span>
				<span class="formInput"><input id="formBookId" type="text" name="book_id" size="8"> <span id="bookName" style="color:#449944"></span></span></p>
			<p><input type="submit" id="formSubmit" name="submit" value="Process Loan"></p>
		</form>
		';
	}
?>
	</div>
</div>
</body>

</html>