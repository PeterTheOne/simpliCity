<?php
	// includes and functions

	require_once("includes/lib/FoursquareAPI.class.php");
	
	require_once("includes/config.inc.php");
	
	require_once("includes/essentials.inc.php");
	require_once("includes/essentials_fs.inc.php");

	// init

	session_start();
	
	fs_setup();

	// display
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>SimpliCity</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta name="Author" content="Andreas Kasch, Peter Grassberger"/>
		<meta name="Description" content="SimpliCity"/>
		<link rel="shortcut icon" type="image/x-icon" href="images/system/logo.ico" />
		<meta name="viewport" content="width=device-width, initial-scale = 1.0, user-scalable = no" />
		<script type="text/javascript" src="script/jquery.js"></script>
		<style type="text/css">
			body {
				background-color: #03638c;
				font-family: Nyala,Verdana,Arial,sans-serif;
				font-size: 12pt;
				width: 100%;
				height: 100%;
				color: rgb(192,192,192);
			}
			
			img {
				display: block;
				margin: 0 auto;
				width: 176px;
			}
		</style>
	</head>
	<body>
		<div id="container">
			<img src="images/auth/simplicity.png" alt="SimpliCity"/>
			<script type="text/javascript">
				$(function(){
					$("#jsactivate").hide();
					$("#authlink").show();
				});
			</script>
			<p id="jsactivate">
				Sie m&uuml;ssen Javascript aktivieren um das Spiel zu spielen!
			</p>
<?php
			echo "<p style=\"display:none\" id=\"authlink\"><a href='" . 
				$foursquare->AuthenticationLink(REDIRECT_URI) . 
				"'><img src=\"images/auth/foursquare.png\" alt=\"Connect to this app via Foursquare\" /></a></p>";
?>
		</div>
	</body>
</html>
