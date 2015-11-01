<?php
	require 'system_variable.php';  // initialize global uses constrain,

    if ( !empty( $_GET['name'] ) )  {
      require $GLOBALS["classRoot"].'LibraryDb.php';
      $myLibrary = new LibraryDb();
      $thisKeyword = $_GET['name'];
      $thisField = $_GET['searchby'];
      if ( $myLibrary->connect() )  {
		$bookList = $myLibrary->getRecords('Books', $thisField, "'%$thisKeyword%'" );
        if ( sizeof($bookList) > 0 ){
          echo $bookList[0][$thisField];
        }
  	  }
  	}
?>
