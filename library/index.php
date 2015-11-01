<?php
  require 'system_variable.php';
  require $GLOBALS["root"].'php_class/login.php';
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
	<div id="sidebar">
		<div class="msgbox">
			<span style="color: #fc661c">Important!</span><br/>
			Course Code: JDPDI0114FC1<br/>
			Library Assignment<br/>
			Student Name: Lam Kwok Shing
		</div>
		<span class="msgbox">
			<span style="color: #fc661c">Tips!</span><br/>
			Login: admin<br/>
			Password: password
		</span>
	</div>
	<?php showLoginResult($logAs); ?>
</div>

</body>
</html>

<?php
function showLoginResult($logAs){	if ( $logAs )  {
		echo '
    		<div id="sysMsg">Welcome! '.$logAs.'.</div>
		';
	} else {
		echo '
			<div id="loginbox">
				<div id="loginboxheader">Please login to the system</div>
				<form method="post" action="'.$_SERVER["PHP__SELF"].'">
					<table border="1" cellpadding="2" cellspacing="0">
						<tr>
							<td>Login</td>
							<td><input type="text" name="login" size="10"></td>
						</tr>
						<tr>
							<td>Password</td>
							<td><input type="password" name="password" size="10"></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="submit" value="Login"></td>
						</tr>
					</table>
				</form>
			</div>
		';
	}
}
?>