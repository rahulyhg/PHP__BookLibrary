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
	<div id="page-title"><a href="search_transaction.php">Transactions</a></div>
	<div id="page-title-active">Readers</div>
	<div id="page-title"><a href="search_book.php">Books</a></div>
	<div id="page-content">
		<div id="page-header">Search a reader</div>
		<div id="sidebar">
			<span class="msgbox">
				<span style="color: #fc661c">Tips!</span><br/>This search engine can display all the records by using the name, tel or ID No. (partial information is acceptable).
			</span>
		</div>
		<p>In this section, you can search a reader, edit the record, or delete the record.</p>
		<form method="post" action="<?php echo $_SERVER["PHP__SELF"] ?>">
			<p>Search by:
				<span style="padding-right: 10px"><input type="radio" name="searchby" value="name" checked> Name</span>
				<span style="padding-right: 10px"><input type="radio" name="searchby" value="tel"> Tel</span>
				<span style="padding-right: 10px"><input type="radio" name="searchby" value="id_cert"> ID No.</span>
			</p>
			<p>Seach Keyword: <input type="text" name="keyword" size="34">
				<input type="submit" name="submit" value="Search"></p>
		</form>

<?php

	if ( isset( $_POST['submit'] ) ){
		if ( empty( $_POST['keyword'] ) ){
			echo '<p class="warning">Please enter the search keyword.</p>';
		} else  {
			getFormData();
			require $GLOBALS["classRoot"].'LibraryDb.php';
			$myLibrary = new LibraryDb();
			if ( $myLibrary->connect() ){
				// search all book id that match the search keyword
				$readerList = Array();
				if ( $thisSearchCol == 'name' )  {					$givenNameResult = $myLibrary->getRecords('Readers', 'given_name', "'%$thisKeyword%'");
					if ( sizeof($givenNameResult) ){						$readerList = array_merge( $readerList, $givenNameResult );
					}
					$surnameResult = $myLibrary->getRecords('Readers', 'surname', "'%$thisKeyword%'");
					if ( sizeof($surnameResult) ){
						$readerList = array_merge( $readerList, $surnameResult );
					}
				} else {
					$readerList = $myLibrary->getRecords('Readers', $thisSearchCol, "'%$thisKeyword%'");
				}
			}

			if ( sizeof($readerList) > 0 )  {
				$filter .= " (";
				$r = 0;
				$readerIdList = Array();
				if ( sizeof($readerList) ){
					foreach( $readerList as $thisReader ){
						array_push( $readerIdList, $thisReader['card_id'] );
					}
					$readerIdList = array_unique($readerIdList);
				}
				foreach ( $readerIdList as $thisReaderId )  {
					if ( $r++ == 0 )  {
						$filter .= $thisReaderId;
					} else {
						$filter .= ', '.$thisReaderId;
					}
				}
				$filter .= ")";
				require_once $GLOBALS["classRoot"].'Readers.php';
				$myTable = new Reader();
				$records = $myLibrary->getRecords($myTable->getTableName(),'card_id',$filter);
				echo '<hr/>';
				$compile = true;
				echo $myTable->getTable($records,$compile);
			} else {
				echo '<hr/>';
				echo '<p class="warning">No record found by this keyword!</p>';
			}
		}
	}

  function getFormData()  {
  	global $thisSearchCol, $thisKeyword;

 	$thisSearchCol = $_POST['searchby'];
  	$thisKeyword = $_POST['keyword'];
  }
?>
	</div>
</div>
</body>

</html>