<?php
	session_start();
	
	require_once("includes/essentials.inc.php");
	
	if(isset($_SESSION["authtoken"])){
	
		header("Location: index.php");
		
	} else if(isset($_GET["token"])){
	
		$_SESSION["authtoken"] = sanitizeFilter($_GET["token"]);
		header("Location: index.php");
		
	} else {
	
		$host = $_SERVER[SERVER_NAME].$_SERVER[REQUEST_URI];
		header("Location: http://petergrassberger.at/pro5/prototypes/foursquare-api_php-foursquare/?host=".$host);
		
	}
	
?>