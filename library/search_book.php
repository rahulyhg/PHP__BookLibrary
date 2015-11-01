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
	<div id="page-title"><a href="search_transaction.php">Transactions</a></div>
	<div id="page-title"><a href="search_reader.php">Readers</a></div>
	<div id="page-title-active">Books</div>
	<div id="page-content">
		<div id="page-header">Search a book</div>
		<div id="sidebar">
			<span class="msgbox">
				<span style="color: #fc661c">Tips!</span><br/>This search engine can display all the records by using the name of book, author or phblisher.
			</span>
		</div>
		<p>In this section, you can search a book, edit the record, or delete the record.</p>
		<form method="post" action="<?php echo $_SERVER["PHP__SELF"] ?>">
			<p>Search by:
				<span style="padding-right: 10px"><input type="radio" id="byBookName" name="searchby" value="name" checked> Book Name</span>
				<span style="padding-right: 10px"><input type="radio" id="byAuthor" name="searchby" value="author"> Author</span>
				<span style="padding-right: 10px"><input type="radio" id="byPublisher" name="searchby" value="publisher"> Publisher</span>
			</p>

			<p>Seach Keyword: <input type="text" id="formKeyword" name="keyword" size="50" placeholder="Full name or partial name also acceptable">
				<input type="submit" name="submit" value="Search"><span id="mostCloseResult" style="color:#996699; font-size: 0.75em"></span></p>
		</form>

<?php

	if ( isset( $_POST['submit'] ) )  {
		if ( empty( $_POST['keyword'] ) )  {
			echo '<p class="warning">Please enter the search keyword.</p>';
		} else  {
			getFormData();
			require $GLOBALS["classRoot"].'LibraryDb.php';
			$myLibrary = new LibraryDb();
			if ( $myLibrary->connect() )  {
				// search all book id that match the search keyword
				$bookList = $myLibrary->getRecords('Books', $thisSearchCol, "'%$thisKeyword%'");
			}

			if ( sizeof($bookList) > 0 )  {
				$filter .= " (";
				$b = 0;
				foreach ( $bookList as $thisBook ){					if ( $b++ == 0 )  {
						$filter .= $thisBook['book_id'];
					} else  {
						$filter .= ", ".$thisBook['book_id'];
					}
				}
				$filter .= ")";
				require_once $GLOBALS["classRoot"].'Books.php';
				$myTable = new Book();
				$records = $myLibrary->getRecords($myTable->getTableName(),'book_id',$filter);
				echo '<hr/>';
				$compile = true;
				echo $myTable->getTable($records,$compile);
			} else {				echo '<hr/>';
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