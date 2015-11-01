<?php
  require_once '../system_variable.php';
  require_once $GLOBALS["classRoot"].'login.php';
  $logAs = checkLogin(); // from class login
?>
<html>
<head>
<title>Lam Kwok Shing's Library System</title>
<link rel="stylesheet" type="text/css" href="../css/admin_setting.css" />
</head>
<body>
<div class="header">Database Management - Setting sample data into database...</div>
<?php
  require_once $GLOBALS["classRoot"].'databasetopmenu.php';
  echo databaseTopMenu();
?>

<?php

	require $GLOBALS["classRoot"].'LibraryDb.php';
	$myLibrary = new LibraryDb();

	if ( $myLibrary->connect() )  {
		// below list all major works in this page.
		setSampleDataForClasses();
		setSampleDataForReaders();
		setSampleDataForBooks();
		setSampleDataForLoans();
	}

	// Create a table store the user's class details.
	function setSampleDataForClasses()  {
		global $myLibrary, $myTable;
		$tableName=$GLOBALS['tableUserClasses'];
		require_once $GLOBALS["classRoot"].$tableName.'.php';
	    $sample[0] = array(	name		=>	'Non-degree student',
    						allowance	=>	3
    						);
		$sample[1] = array(	name		=>	'Undergraduate student',
    						allowance	=>	5
    						);
    	$sample[2] = array(	name		=>	'Master student',
    						allowance	=>	10
    						);
	    $sample[3] = array(	name		=>	'Doctor student',
    						allowance	=>	20
    						);
	    $sample[4] = array(	name		=>	'Teaching staff',
    						allowance	=>	50
    						);
		echo '<hr/>';
    	for ( $i=0; $i < sizeof($sample); $i++ ){
			$myTable = new UserClass(
								$i+1,
								$sample[$i]['name'],
								$sample[$i]['allowance'] );
			$execution = $myLibrary->addRec($tableName, $i+1,
							array(
								$myTable->getName(),
								$myTable->getAllowance()
    						) );
	    	if ( $execution ){
    			echo "Table ".$tableName." ID#".($i+1)." added.<br/>";
    		} else {
    			echo "Table ".$tableName." ID#".($i+1)." add record failed.<br/>";
	    	}
		}
		$records = Array();
		$records = $myLibrary->getRecords($tableName);
		echo $myTable->getTable($records);
	}

// Create a table store the reader's information of each library card.
	function setSampleDataForReaders()  {
		global $myLibrary, $myTable;
		$tableName=$GLOBALS['tableReaders'];
		require_once $GLOBALS["classRoot"].$tableName.'.php';

	    $sample[0] = array(	given_name		=>	'Joey',
							surname			=>	'Yung',
							register_date	=>	'2012-09-03',
							tel				=>	'92154422',
							id_cert			=>	'K854596(3)',
    						class_id		=>	1,
    						activated		=>	1
    						);
	    $sample[1] = array(	given_name		=>	'Hong Yu',
							surname			=>	'Chu',
							register_date	=>	'2012-09-05',
							tel				=>	'51114544',
							id_cert			=>	'Y154112(6)',
    						class_id		=>	3,
    						activated		=>	1
    						);
	    $sample[2] = array(	given_name		=>	'Wing Lam',
							surname			=>	'Mak',
							register_date	=>	'2012-09-08',
							tel				=>	'62236060',
							id_cert			=>	'Z151444(1)',
    						class_id		=>	2,
    						activated		=>	1
    						);
	    $sample[3] = array(	given_name		=>	'Siu Kei',
							surname			=>	'Cheung',
							register_date	=>	'2012-10-11',
							tel				=>	'94541237',
							id_cert			=>	'K945211(3)',
    						class_id		=>	4,
    						activated		=>	1
    						);
	    $sample[4] = array(	given_name		=>	'Chi Lam',
							surname			=>	'Lee',
							register_date	=>	'2013-01-27',
							tel				=>	'62984156',
							id_cert			=>	'A652154(0)',
    						class_id		=>	5,
    						activated		=>	1
    						);
		echo '<hr/>';
		$idStartValue = 100001;
    	for ( $i=0; $i < sizeof($sample); $i++ ){
			$myTable = new Reader(
								$i+$idStartValue,
								$sample[$i]['given_name'],
								$sample[$i]['surname'],
								$sample[$i]['register_date'],
								$sample[$i]['tel'],
								$sample[$i]['id_cert'],
								$sample[$i]['class_id'],
								$sample[$i]['activated'] );
			$execution = $myLibrary->addRec($tableName, $myTable->getCardId(),
							array(
								$myTable->getGivenName(),
								$myTable->getSurname(),
								$myTable->getRegisterDate(),
								$myTable->getTel(),
								$myTable->getIdCert(),
								$myTable->getClassId(),
								$myTable->getActivated()
    						) );
	    	if ( $execution ){
    			echo "Table ".$tableName." ID#".($i+1)." added.<br/>";
    		} else {
    			echo "Table ".$tableName." ID#".($i+1)." add record failed.<br/>";
	    	}
		}
		$records = Array();
		$records = $myLibrary->getRecords($tableName);
		echo $myTable->getTable($records);
	}

// Create a table store the book's information of each book owned by the library.
function setSampleDataForBooks()  {
	global $myLibrary;
	$tableName=$GLOBALS['tableBooks'];
	require_once $GLOBALS["classRoot"].$tableName.'.php';
    $sample[0] = array(	name		=>	'Theory and Practice of Counseling and Psychotheraphy',
    					edition		=>	'8',
    					author		=>	'Gerald Corey',
    					publisher	=> 'Brooks/Cole',
    					year 		=> 2009,
    					status		=> 1
    					);
    $sample[1] = array(	name		=> 'HTML Programmer\'s Reference',
    					edition		=> '1',
    					author		=> 'McGraw-Hill',
    					publisher	=> 'Thomas A. Powell and Dan Whitworth',
    					year 		=> 1998,
    					status		=> 2
    					);
    $sample[2] = array(	name		=> 'Java : how to program',
    					edition		=> '4',
    					author		=> 'H.M. Deitel, P.J. Deitel',
    					publisher	=> 'Gerald Corey',
    					year 		=> 2001,
    					status		=> 1
    					);
    $sample[3] = array(	name		=> 'Women eho spied',
    					edition		=> '1',
    					author		=> 'Hoehling, A. A.',
    					publisher	=> 'Madison Books',
    					year 		=> 1992,
    					status		=> '0'
    					);
    $sample[4] = array(	name		=> 'Audio enthusiasts handbook',
    					edition		=> '1',
    					author		=> 'Babani, Bernard B.',
    					publisher	=> 'Bernards',
    					year 		=> 1975,
    					status		=> 2
    					);

	echo '<hr/>';
	$idStartValue = 1;
    for ( $i=0; $i < sizeof($sample); $i++ ){
		$myTable = new Book(
							$i+$idStartValue,
							$sample[$i]['name'],
							$sample[$i]['edition'],
							$sample[$i]['author'],
							$sample[$i]['publisher'],
							$sample[$i]['year'],
							$sample[$i]['status'] );

    	$execution = $myLibrary->addRec( $tableName, $myTable->getBookId(),
    									 array( $myTable->getName(),
    									 	$myTable->getEdition(),
	    									$myTable->getAuthor(),
    										$myTable->getPublisher(),
    										$myTable->getYear(),
    										$myTable->getStatus()
    									 	)
    									 );

    	if ( $execution ){
    		echo 'Table '.$tableName.' ID#'.($i+1).' added.<br/>';
    	} else {
    		echo 'Table '.$tableName.' ID#'.($i+1).' add record failed.<br/>';
    	}
    }
	$records = Array();
	$records = $myLibrary->getRecords($tableName);
	echo $myTable->getTable($records);
}

// Create a table store the loans information of each loan record.
function setSampleDataForLoans()  {

	global $myLibrary;
	$tableName=$GLOBALS['tableLoans'];
	require_once $GLOBALS["classRoot"].$tableName.'.php';
    $sample[0] = array(	startDate	=> '2014-01-11',
    					cardId		=> 100001,
    					bookId		=> 1,
    					status 		=> 1,
    					);
    $sample[1] = array(	startDate	=> '2014-02-01',
    					cardId		=> 100002,
    					bookId		=> 4,
    					status 		=> 0,
    					);
    $sample[2] = array(	startDate	=> '2014-01-26',
    					cardId		=> 100004,
    					bookId		=> 2,
    					status 		=> 1,
    					);
    $sample[3] = array(	startDate	=> '2014-02-16',
    					cardId		=> 100003,
    					bookId		=> 5,
    					status 		=> 1,
    					);
    $sample[4] = array(	startDate	=> '2014-02-09',
    					cardId		=> 100001,
    					bookId		=> 1,
    					status 		=> 2,
    					);

	echo '<hr/>';
	$idStartValue = 1;
    for ( $i=0; $i < sizeof($sample); $i++ ){
		$myTable = new Loan(
							$i+$idStartValue,
							$sample[$i]['startDate'],
							$sample[$i]['cardId'],
							$sample[$i]['bookId'],
							$sample[$i]['status'] );

    	$execution = $myLibrary->addRec( $tableName, $myTable->getLoanId(),
    									 array( $myTable->getTransactionDate(),
    									 	$myTable->getDueDate(),
	    									$myTable->getCardId(),
    										$myTable->getBookId(),
    										$myTable->getStatus()
    									 	)
    									 );
    	if ( $execution ){
    		echo 'Table '.$tableName.' ID#'.($i+1).' added.<br/>';
    	} else {
    		echo 'Table '.$tableName.' ID#'.($i+1).' add record failed.<br/>';
    	}
    }
	$records = Array();
	$records = $myLibrary->getRecords($tableName);
	echo $myTable->getTable($records);
}

?>

<?php echo "<p class='warning'>".$GLOBALS['debugMsg']."</p>"; ?>

</body>
</html>