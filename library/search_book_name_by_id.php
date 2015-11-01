<?php
	require 'system_variable.php';  // initialize global uses constrain,

    if ( !empty( $_GET['id'] ) )  {
      require $GLOBALS["classRoot"].'LibraryDb.php';
      $myLibrary = new LibraryDb();
      $thisKeyword = $_GET['id'];
      if ( $myLibrary->connect() )  {
		$bookList = $myLibrary->getRecords('Books', 'book_id', $thisKeyword );
        if ( sizeof($bookList) > 0 ){
	      echo $bookList[0]['name'];
        }
  	  }
  	}
?>
