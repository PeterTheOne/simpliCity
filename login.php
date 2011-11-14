<?php
	session_start();
	if(isset($_SESSION["authtoken"])){
		header("Location: index.php");
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>SimpliCity</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta name="Author" content="Andreas Kasch"/>
		<meta name="Description" content="SimpliCity"/>
		<link rel="stylesheet" type="text/css" href="styles/styles.css" />
		<link rel="shortcut icon" type="image/x-icon" href="images/system/logo.ico" />
		<meta name="viewport" content="width=device-width, initial-scale = 1.0, user-scalable = no" />
		<script type="text/javascript" src="script/jquery.js"></script>
		<script type="text/javascript" src="script/style.js"></script>
	</head>
	<body>
		<div id="container">
			<iframe>
			</iframe id="auth-file" name="auth-file" src="">
		</div>
	</body>
</html>