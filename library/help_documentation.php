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
	<div id="page-title-active">Help</div>
	<div id="page-title"><a href="about.php">About</a></div>
	<div id="page-content">
		<p>If you find any problem in this site, please send an email to me at <a href="mailto:toni_lam@hotmail.com">toni_lam@hotmail.com</a>.<br/>
			Thank you.</p>
		<hr/>
		<h4>Assignment Topic:</h4>
		<p>You are required to submit the following 3 assignments by the end of this course.</p>
		<ol>
			<li>
				Design a database modelling a library book loaning system. The number of tables should be at least 3.<br/>
				You are required to create the ER diagram and also the related SQL statements. Each table should have at least 5 sample records.
			</li>
			<li>
				Using PHP and Javascript to design the logic of the system. You are required to design the subsytem of managing the reader
				<ol type="a">
					<li>Add a new reader</li>
					<li>Delete a reader</li>
					<li>List all the readers</li>
				</ol>
				Make sure the data input are all validated.
			</li>
			<li>
				Using CSS to design the interface of the subsystem mentioned above.
			</li>
		</ol>
	</div>
</div>
</body>

</html>