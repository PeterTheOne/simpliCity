<?php
	session_start();
	
	if(isset($_SESSION["authtoken"])){
	
		header("Location: index.php");
		
	} else if(isset($_GET["token"])){
	
	
		require_once("includes/lib/FoursquareAPI.class.php");
		define("CLIENT_ID",		"GPP0FG0DZI3ES5JKLNBJWZDMEITYI2XECBVTSW2GL3ZJCHGT");
		define("CLIENT_SECRET",	"5P3ABYS1CSVHXVEEAHWZP1A42DWZ4DTXROA5SAFCTXZCJNMX");
		
		$foursquare = new FoursquareAPI(CLIENT_ID, CLIENT_SECRET);
		
		$foursquare->SetAccessToken($_GET["token"]);
		
		$request = $foursquare->GetPrivate("users/self");
		$details = json_decode($request);
		$firstName = $details->response->user->firstName;
		echo "<p>Thanks for the authentication $firstName!</p>";
		
		
	} else {
		$host = $_SERVER[SERVER_NAME].$_SERVER[REQUEST_URI];
		header("Location: http://petergrassberger.at/pro5/prototypes/foursquare-api_php-foursquare/?host=".$host);
	}
?>