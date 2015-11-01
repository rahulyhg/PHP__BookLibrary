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
	<div id="page-title-active">Transactions</div>
	<div id="page-title"><a href="search_reader.php">Readers</a></div>
	<div id="page-title"><a href="search_book.php">Books</a></div>
	<div id="page-content">
		<div id="page-header">Search a transaction</div>
		<div id="sidebar">
			<span class="msgbox">
				<span style="color: #fc661c">Tips!</span><br/>The transaction date is set to 3 months ago to today by default.
			</span>
		</div>
		<p>In this section, you can search a transaction, edit the record, or delete the record.</p>
		<form method="post" action="<?php echo $_SERVER["PHP__SELF"] ?>">
			<p style="color: #009000">You may search by either reader's name or book's name, and search by both name is also welcomed.</p>
			<p style="indent: 10px">Reader's name: <input type="text" name="reader" size="35"></p>
			<p style="indent: 10px">Book's name: <input ID="bookName" type="text" name="book" size="50"></p>
			<p style="indent: 10px">Transaction Date: from
			<select type="select" name="fromday">
	        	<?php
				$today = getdate();
				$twoWeeksAgo = strtotime(" -14 days");
				$monthAbbr = array( 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC' );
				for ( $i = 1; $i <= 31; $i++ )  {
					if ( $i == date('d', $twoWeeksAgo) )  {
						echo "<option selected>".$i."</option>";
					} else {
						echo "<option>".$i."</option>";
					}
				}
				?>
			</select>
			<select type="select" name="frommonth">
    	    	<?php
					for ( $i = 1; $i <= 12; $i++ )  {
						if ( $i == date('m', $twoWeeksAgo) )  {
							echo "<option value='$i' selected>".$monthAbbr[$i-1]."</option>";
						} else {
							echo "<option value='$i'>".$monthAbbr[$i-1]."</option>";
						}
					}
				?>
			</select>
			<select type="select" name="fromyear">
				<?php
					for ( $i = (date('Y', $twoWeeksAgo)-4); $i <= date('Y', $twoWeeksAgo); $i++ )  {
						if ( $i == date('Y', $twoWeeksAgo) )  {
							echo "<option selected>".$i."</option>";
						} else {
							echo "<option>".$i."</option>";
						}
					}
				?>
			</select>
			to
			<select type="select" name="today">
    	    	<?php
					for ( $i = 1; $i <= 31; $i++ )  {
						if ( $i == $today["mday"] )  {
							echo "<option selected>".$i."</option>";
						} else {
							echo "<option>".$i."</option>";
						}
					}
				?>
			</select>
			<select type="select" name="tomonth">
     		   <?php
					for ( $i = 1; $i <= 12; $i++ )  {
						if ( $i == $today["mon"] )  {
							echo "<option value='$i' selected>".$monthAbbr[$i-1]."</option>";
						} else {
							echo "<option value='$i'>".$monthAbbr[$i-1]."</option>";
						}
					}
				?>
			</select>
			<select type="select" name="toyear">
    	    	<?php
					for ( $i = ($today["year"]-4); $i <= $today["year"]; $i++ )  {
						if ( $i == $today["year"] )  {
							echo "<option selected>".$i."</option>";
						} else {
							echo "<option>".$i."</option>";
						}
					}
				?>
			</select>
			<span style="font-size: 10pt; color: #AAAAAA">(Date-Month-Year)</span></p>
			<p>Status<span style="color: #FF0000">*</span>:
    		    <span style="padding-right: 15px"><input type="checkbox" name="status[]" value="1" checked> Borrow</span>
        		<span style="padding-right: 15px"><input type="checkbox" name="status[]" value="2" checked> Return</span>
		        <span style="padding-right: 15px"><input type="checkbox" name="status[]" value="0" checked> Void</span>
			</p>
			<p><input type="submit" name="submit" value="Search"></p>
		</form>

<?php

	if ( isset( $_POST['submit'] ) ){
		require_once $GLOBALS["classRoot"].'Readers.php';
		require_once $GLOBALS["classRoot"].'Books.php';
		require_once $GLOBALS["classRoot"].'Loans.php';

		if ((( empty($_POST['reader']) ) or ( empty($_POST['book']) )) and ( empty( $_POST['status'] ) )){
			echo '<p class="warning">At least one name and one status should be entered.</p>';
		} else  {
			getFormData();
			require $GLOBALS["classRoot"].'LibraryDb.php';
			$myLibrary = new LibraryDb();
			if ( $myLibrary->connect() ){
				if ( !empty($thisReader) )  {
					// search all reader's card id that match the reader's given name or surname search keyword
					$readerList = Array();
					$givenNameResult = $myLibrary->getRecords('Readers', 'given_name', "'%$thisReader%'");
					if ( sizeof($givenNameResult) ){
						$readerList = array_merge( $readerList, $givenNameResult );
					}
					$surnameResult = $myLibrary->getRecords('Readers', 'surname', "'%$thisReader%'");
					if ( sizeof($surnameResult) ){
						$readerList = array_merge( $readerList, $surnameResult );
					}
					$readerIdList = Array();
					if ( sizeof($readerList) ){						foreach( $readerList as $thisReader ){							array_push( $readerIdList, $thisReader['card_id'] );
						}
						$readerIdList = array_unique($readerIdList);
					}
 				}

				if ( !empty($thisBook) )  {
					// search all book id that match book name search keyword
					$bookList = $myLibrary->getRecords('Books', 'name', "'%$thisBook%'");
				}

				$transactionList = $myLibrary->getRecords('Loans', 'transaction_date', "'$thisTransactionStart'", "'$thisTransactionEnd'" );

				$readerRecNo = sizeof($readerIdList);
				$bookRecNo = sizeof($bookList);
				$searchResult = Array();
				foreach( (array)$transactionList as $thisRow ){					if ( $readerRecNo > 0 ){						foreach( $readerIdList as $thisReaderId ){							if ( $thisRow['card_id'] == $thisReaderId ){								if ( $bookRecNo > 0 ){									foreach( $bookList as $thisBook ){
										if ( $thisRow['book_id'] == $thisBook['book_id'] ){											if ( strpos( $thisStatus, '['.$thisRow['status'].']' ) ){												array_push( $searchResult, $thisRow );
											}
										}
									}
								} else {									if ( strpos( $thisStatus, '['.$thisRow['status'].']' ) ){
										array_push( $searchResult, $thisRow );
									}
								}
							}
						}					} elseif ( $bookRecNo > 0 ){
						foreach( $bookList as $thisBook ){
							if ( $thisRow['book_id'] == $thisBook['book_id'] ){
								if ( $readerRecNo > 0 ){
									foreach( $readerIdList as $thisReaderId ){
										if ( $thisRow['card_id'] == $thisReaderId ){											if ( strpos( $thisStatus, '['.$thisRow['status'].']' ) ){
												array_push( $searchResult, $thisRow );
											}
										}
									}
								} else {
									if ( strpos( $thisStatus, '['.$thisRow['status'].']' ) ){
										array_push( $searchResult, $thisRow );
									}
								}
							}
						}
					}
				}

				if ( sizeof(searchResult) > 0 )  {					require_once $GLOBALS["classRoot"].'Loans.php';
					$myTable = new Loan();
					echo '<hr/>';
					$compile = true;
					echo $myTable->getTable($searchResult,$compile);
				} else {
					echo '<hr/>';
					echo '<p class="warning">No record found by this criteria!</p>';
				}
			}
		}
	}

	function getFormData()  {
		global $thisReader, $thisBook, $thisTransactionStart, $thisTransactionEnd, $thisStatus;

		$thisReader = $_POST['reader'];
		$thisBook = $_POST['book'];
		$getDate = date_create( $_POST['fromyear'].'-'.$_POST['frommonth'].'-'.$_POST['fromday'] );
		$thisTransactionStart = date_format( $getDate, 'Y-m-d' );
		$getDate = date_create( $_POST['toyear'].'-'.$_POST['tomonth'].'-'.$_POST['today'] );
		$thisTransactionEnd = date_format( $getDate, 'Y-m-d' );
		$getStatus = $_POST['status'];

		$statusNum = count( $getStatus );
		for ( $i = 0; $i < $statusNum; $i++ )  {
			$thisStatus .= ' ['.$getStatus[$i].']';
		}
	}

?>
	</div>
</div>
</body>

</html>