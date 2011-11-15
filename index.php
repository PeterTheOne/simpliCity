<?php
	session_start();
	
	require_once("includes/config.inc.php");
	
	require_once("includes/lib/FoursquareAPI.class.php");
	require_once("includes/essentials.inc.php");
	require_once("includes/database.inc.php");
	
	setupFoursquare();
	
	if(!isset($_SESSION["authtoken"]) || !checkUser()){
		header("Location: logout.php");
	}
	
	/*
	 *
	 *	setup the foursquare connection
	 *
	*/
	function setupFoursquare(){
	
		global $foursquare;
		
		$foursquare = new FoursquareAPI(CLIENT_ID, CLIENT_SECRET);
		
		if(isset($_SESSION["authtoken"])){
			$foursquare->SetAccessToken($_SESSION["authtoken"]);
		}
		
	}
	
	
	/*
	 *
	 *	check if Token is valid and did not change
	 *	check users Timestamp and give him new citizens
	 *
	*/
	function checkUser(){
		
		global $foursquare;
		
		$request = $foursquare->GetPrivate("users/self");
		$details = json_decode($request);
		
		//printarray($details);
		
		$err = isset($details->meta->code)? $details->meta->code : 0;
		if($err >= 400 && $err <= 500){
			//echo $err;
			//echo "<p>".$details->meta->errorType.": ".$details->meta->errorDetail."</p>";
			return false;
			
		} else {
			
			$userID = $details->response->user->id;
			$token = $_SESSION["authtoken"];
			$time = date("Y-m-d");
			$citizen = CITIZEN_STARTUP;
			
			db_connect();
			
			if($result = mysql_query(
				"SELECT * FROM users WHERE ID='$userID'"
			)){
				
				$exists = false;
				while ($line = mysql_fetch_array($result)){
					$exists = true;
					//printarray($line);
					$setToken = "";
					if($line["Token"] != $token){
						$setToken = "Token='$token',";
					}
					$alterCitizen = "";
					if($line["LoginDate"] != $time){
						$citizen = $line["UnusedCitizen"] + CITIZEN_INCREASE;
						$alterCitizen = "UnusedCitizen='$citizen',";
					}
					if($setToken != "" || $alterCitizen != "" || $line["LoginDate"] != $time){
						mysql_query(
							"UPDATE users SET $setToken $alterCitizen LoginDate='$time' WHERE ID='$userID'"
						);
					} else {
						//echo "nothing changed";
					}
				}
				
				if(!$exists){
					mysql_query(
						"INSERT INTO users (ID,Token,LoginDate,UnusedCitizen) VALUES ('$userID','$token','$time','$citizen')"
					);
				}
				
			
			} else {
				
				db_disconnect();
				return false;
			
			}
			
			db_disconnect();
			
			return true;
		}
		
	}
	
	
	/*
	 *
	 *	draw the page
	 *
	*/
	require_once("template/header.tpl.php");
	require_once("template/cityview.tpl.php");
	require_once("template/citymenu.tpl.php");
	require_once("template/footer.tpl.php");
?>