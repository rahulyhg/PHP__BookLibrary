<?php

function mainmenu( $logStatus )  {
  echo '<ul>';
  if ( $logStatus ) {
    echo '
    <li id="MainMenu1" onClick="showChildMenu(1)" onMouseOver="showChildMenu(1)" onMouseOut="showChildMenu()">System
      <ul id="SubMenu1">
        <li><a href="./admin/checkDatabase.php">Check Database</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </li>
    <li id="MainMenu2" onClick="showChildMenu(2)" onMouseOver="showChildMenu(2)" onMouseOut="showChildMenu()">New
      <ul id="SubMenu2">
        <li><a href="new_transaction.php">New loan record</a></li>
        <li><a href="return_book.php">Return book</a></li>
        <li><a href="new_reader.php">New reader</a></li>
        <li><a href="new_book.php">New book</a></li>
      </ul>
    </li>
    <li id="MainMenu3" onClick="showChildMenu(3)" onMouseOver="showChildMenu(3)" onMouseOut="showChildMenu()">Search
      <ul id="SubMenu3">
        <li><a href="search_transaction.php">Search a transaction</a></li>
        <li><a href="search_reader.php">Search a readers</a></li>
        <li><a href="search_book.php">Search a book</a></li>
      </ul>
    </li>
    <li id="MainMenu4" onClick="showChildMenu(4)" onMouseOver="showChildMenu(4)" onMouseOut="showChildMenu()">View
      <ul id="SubMenu4">
        <li><a href="list_transactions.php">List all transactions</a></li>
        <li><a href="list_readers.php">List all readers</a></li>
        <li><a href="list_books.php">List all books</a></li>
      </ul>
    </li>
    ';
  } else {
    echo '
    <li id="MainMenu1" onClick="showChildMenu(1)" onMouseOver="showChildMenu(1)" onMouseOut="showChildMenu()">System
      <ul id="SubMenu1">
        <li><a href="index.php">Login</a></li>
      </ul>
    </li>
    ';
  }
  echo '
    <li id="MainMenu5" onClick="showChildMenu(5)" onMouseOver="showChildMenu(5)" onMouseOut="showChildMenu()">Help
      <ul id="SubMenu5">
        <li><a href="help_documentation.php">Help Documentation</a></li>
        <li><a href="about.php">About...</a></li>
      </ul>
    </li>
  </ul>
  ';
}

?>