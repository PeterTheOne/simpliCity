<?php
	session_start();
	
	if(isset($_SESSION["authtoken"])){
	
		header("Location: index.php");
		
	} else if(isset($_GET["token"])){
	
	
		require_once("includes/lib/FoursquareAPI.class.php");
		require_once("includes/essentials.inc.php");
		define("CLIENT_ID",		"GPP0FG0DZI3ES5JKLNBJWZDMEITYI2XECBVTSW2GL3ZJCHGT");
		define("CLIENT_SECRET",	"5P3ABYS1CSVHXVEEAHWZP1A42DWZ4DTXROA5SAFCTXZCJNMX");
		
		$foursquare = new FoursquareAPI(CLIENT_ID, CLIENT_SECRET);
		
		$foursquare->SetAccessToken($_GET["token"]);
		
		$request = $foursquare->GetPrivate("users/self");
		$details = json_decode($request);
		
		printarray($details);
		
		$err = isset($details->meta->code)? $details->meta->code : 0;
		if($err != 400 && $err != 401 && $err != 403 && $err != 404 && $err != 405 && $err != 500){
			
			$_SESSION["authtoken"] = $_GET["token"];
			//header("Location: index.php");
			
		} else {
			echo "<p>".$details->meta->errorType.": ".$details->meta->errorDetail."</p>";
		}
		
		
	} else {
		$host = $_SERVER[SERVER_NAME].$_SERVER[REQUEST_URI];
		header("Location: http://petergrassberger.at/pro5/prototypes/foursquare-api_php-foursquare/?host=".$host);
	}
?>