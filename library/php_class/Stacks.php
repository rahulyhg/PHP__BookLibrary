<?php
/* Field declaration:
     stack_id                [PK] Store the stack id. This field should be in auto increment.
     stack_date              Store the date when this stack is created.
     borrower_id             [FK] Store which one is waiting for the book.
     book_id                 [FK] Store which book is being requested.
*/

function newStacks()  { // Construct Object
  $tableName = getStacksTableName();
  $sql = "create table $tableName (
            stack_id        int(10)    auto_increment primary key,
            stack_date      date,
            borrower_id     int(10),
            book_id         int(10),
            foreign key (borrower_id) references lib_Readers(card_id),
            foreign key (book_id) references lib_Books(book_id)
          ) engine innodb;";

  if ( $result = mysql_query( $sql ))  {
  	$returnValue .= "<p>Table '$tableName' created!</p>";
  } else {
   	$returnValue .= "<p>Table '$tableName' cannot be created!</p>";
  }

  return $returnValue;
}

function clearStacks()  { // Reset Object
  $tableName = getStacksTableName();
  $sql = "select count('stack_id') from ".$tableName;
  $resource = mysql_query( $sql );
  $count = mysql_result( $resource, 0 );
  $sql = "delete from ".$tableName;
  if ( $result = mysql_query( $sql ) )  {
  	$returnValue .= "<p>Table '".$tableName."' cleared. ".$count." record(s) removed.</p>";
  } else {
  	$returnValue .= "<p>Cannot clean table '".$tableName."'. Data is link to other tables or some error occurs during process.</p>";
  }

  return $returnValue;
}

function dropStacks()  { // Destruct Object
  $tableName = getStacksTableName();
  $sql = "select count('stack_id') from ".$tableName;
  $resource = mysql_query( $sql );
  $count = mysql_result( $resource, 0 );
  if ( $count == 0 )  {
	$sql = "drop table ".$tableName;
	if ( $result = mysql_query( $sql ) )  {
  	  $returnValue .= "<p>Table '".$tableName."' dropped.</p>";
	} else {
   	  $returnValue .= "<p>Cannot drop table '".$tableName."'.</p>";
	}
  } else {
	$returnValue .= "<p>Cannot drop table '".$tableName."'. Table still have data.</p>";
  }

  return $returnValue;
}

function getStacksTableName()  {
	$tableName = "lib_Stacks";
	return $tableName;
}

function getStacksDateByID( $thisId )  {
	return getFieldDataByID( 'stack_date', getStacksTableName(), 'card_id', $thisId );
}

function getStacksBorrowerIdByID( $thisId )  {
	return getFieldDataByID( 'borrower_id', getStacksTableName(), 'card_id', $thisId );
}

function getStacksBookIdByID( $thisId )  {
	return getFieldDataByID( 'book_id', getStacksTableName(), 'card_id', $thisId );
}

function getStacksTable()  {
  $tableName = getStacksTableName();
  $sql = "select * from ".$tableName;
  $resource = mysql_query( $sql );

  return buildStacksTable( $resource );
}

function buildStacksTable( $resource )  {
  $tableName = getStacksTableName();
  $returnValue .= "
	<div class='resultTitle'>Record in table '$tableName'</div>
	<div class='resultTable'>
	  <div class='resultRow'>
	    <div class='resultHeader'>Stack ID</div>
	    <div class='resultHeader'>Stack Date</div>
	    <div class='resultHeader'>Borrower ID</div>
	    <div class='resultHeader'>Book ID</div>
	  </div>
  ";
  if ( $resource )  {
    while ( $row = mysql_fetch_array( $resource ) )  {
      $thisStackId = $row['stack_id'];
      $thisStackDate = $row['stack_date'];
      $thisBorrowerId = $row['borrower_id'];
      $thisBookId = $row['book_id'];
      $returnValue .= "
	    <div class='resultRow'>
	      <div class='resultData'>$thisStackId</div>
	      <div class='resultData'>$thisStackDate</div>
	      <div class='resultData'>$thisBorrowerId</div>
	      <div class='resultData'>$thisBookId</div>
	    </div>
	  ";
	}
    $returnValue .= '</div>';
  } else {
    $returnValue .= '</div><p class="warning">** No Table!</p>';
  }

  return $returnValue;
}

function addStacksSampleData()  {
	$tableName = getStacksTableName();
    $sql[0] = "insert into ".$tableName." values (1, '2013-01-22', 100001, 5 )";
    $sql[1] = "insert into ".$tableName." values (2, '2013-01-23', 100002, 4 )";
    $sql[2] = "insert into ".$tableName." values (3, '2013-01-24', 100003, 3 )";
    $sql[3] = "insert into ".$tableName." values (4, '2013-01-25', 100004, 2 )";
    $sql[4] = "insert into ".$tableName." values (5, '2013-01-26', 100005, 1 )";
    $returnValue .= "<p><span style='font-family: courier new; font-size: 10pt'>";
    for ( $i = 0; $i <= 4; $i++ )  {
    	$returnValue .= "SQL >> $sql[$i];<br/>";
    	$result += mysql_query( $sql[$i] );
    }
   	$returnValue .= "</span>Total ".$result." record(s) inserted into '".$tableName."'.<br/>";
    $returnValue .= "</p>";

    return $returnValue;
}

?>