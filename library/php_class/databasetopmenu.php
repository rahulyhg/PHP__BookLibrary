<?php

function databaseTopMenu()  {
  $myMenu = "
    <div class='topmenu'><ul>
	  <li><a href='#'>Manage Database</a>
		<ul>
	  	  <li><a href='checkDatabase.php'>Check Database</a></li>
		  <li><a href='dropDatabase.php'>Drop Database</a></li>
		</ul></li>
	  <li><a href='#'>Manage Tables</a>
		<ul>
		  <li><a href='addTable.php'>Add Tables</a></li>
		  <li><a href='printTable.php'>Print Table</a></li>
		  <li><a href='clearTable.php'>Clear Tables</a></li>
		  <li><a href='dropTables.php'>Drop Tables</a></li>
		</ul></li>
	  <li><a href='#'>Manage Data</a>
		<ul>
		  <li><a href='setSampleData.php'>Set Sample Data</a></li>
		</ul></li>
	  <li><a href='../index.php'>User Interface</a></li>
    </ul></div>
  ";

  return $myMenu;
}

?>
