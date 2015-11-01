<?php

$GLOBALS['mysqlHost'] = "localhost";
$GLOBALS['mysqlLogin'] = "root";
$GLOBALS['mysqlPassword'] = "password";
$GLOBALS['mysqlDbName'] = "library";
$GLOBALS['dbname'] = "library";
$GLOBALS['tablePrefix'] = "lib";
$GLOBALS['scriptDir'] = '/library/';

$GLOBALS['debugMode'] = 0;     // 0 = hidden debug message; 1 = show debug message
$GLOBALS['scriptTitle'] = 'Lam Kwok Shing\'s Assignment - Library System';
$GLOBALS['adminLogin'] = 'admin';
$GLOBALS['adminPassword'] = 'password';
$GLOBALS['root'] = $_SERVER["DOCUMENT_ROOT"] . $GLOBALS['scriptDir'];
$GLOBALS['classRoot'] = $GLOBALS['root'] . 'php_class/';

// Tables required for this system
$GLOBALS['tableUserClasses'] = 'UserClasses';
$GLOBALS['tableUserClassesId'] = 'class_id';
$GLOBALS['tableReaders'] = 'Readers';
$GLOBALS['tableReadersId'] = 'card_id';
$GLOBALS['tableBooks'] = 'Books';
$GLOBALS['tableBooksId'] = 'book_id';
$GLOBALS['tableLoans'] = 'Loans';
$GLOBALS['tableLoansId'] = 'loan_id';

?>
