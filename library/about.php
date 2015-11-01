<?php
  require 'system_variable.php';  // initialize global uses constrain,
  require $GLOBALS["classRoot"].'login.php';
  $logAs = checkLogin(); // from class login
?>
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
	<div id="page-title"><a href="help_documentation.php">Help</a></div>
	<div id="page-title-active">About</div>
	<div id="page-content">
		<div id="page-header">About this site</div>
		<p>This site is create by Lam Kwok Shing, Toni.<br/>
			An assignment for Unisoft course(course code: JDPDI0114FC1).<br/>
		</p>
		<p>&nbsp;</p>
		<p>In this site, you can perform the following action:
			<ul>
				<li>Add new records.</li>
				<li>Search existing records.</li>
				<li>View all existing records.</li>
				<li>Edit existing records.</li>
				<li>Remove existing records. (virtually mark the record as removed/inactive/void)</li>
				<li>Delete existing records. (Permanently delete from database)</li>
			</ul>
		</p>
		<p>&nbsp;</p>
		<p>Tables used in the site:
			<ol>
				<li>lib_useclasses<br/>
    		SQL create table statement:<br/>
    		<span style="font-family: courier new; font-size: 0.8em;">create lib_userclasses(
				class_id	int(3) auto_increment primary key,
				name		varchar(30),
				allowance	int(3)
			) engine innodb;
				</span></li>
				<li>lib_readers<br/>
    		SQL create table statement:<br/>
    		<span style="font-family: courier new; font-size: 0.8em;">create lib_readers(
				card_id			int(10) auto_increment primary key,
				given_name		varchar(30),
				surname			varchar(20),
				register_date	date,
				tel				varchar(16),
				id_cert			varchar(12),
				class_id		int(3),
				activated		boolean,
				foreign key (class_id) references lib_userclasses(class_id)
			) engine innodb;
				</span></li>
				<li>lib_books<br/>
    		SQL create table statement:<br/>
    		<span style="font-family: courier new; font-size: 0.8em;">create lib_books(
    			book_id       int(10) auto_increment primary key,
				name          varchar(256),
				edition       int(3),
				author        varchar(256),
				publisher     varchar(100),
				year          int(4),
				status        int(3)
			) engine innodb;
				</span></li>
				<li>lib_loans<br/>
    		SQL create table statement:<br/>
    		<span style="font-family: courier new; font-size: 0.8em;">create lib_loans(
				loan_id	int(10) auto_increment primary key,
				transaction_date			date,
				due_date					date,
				card_id						int(10),
				book_id						int(10),
				status						int(1),
				foreign key (card_id) references lib_Readers(card_id),
				foreign key (book_id) references lib_Books(book_id)
			) engine innodb;
				</span></li>
			</ol>
		</p>
	</div>
</div>
</body>

</html>