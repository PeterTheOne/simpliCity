<?php
	session_start();
	
	require_once("includes/config.inc.php");
	
	require_once("includes/lib/FoursquareAPI.class.php");
	require_once("includes/essentials.inc.php");
	require_once("includes/database.inc.php");
	
	setupFoursquare();
	
	checkAuthentication();
	
	/*
	 *
	 *	check if the user token has been checked
	 *	in this session and today
	 *	sets AccessToken
	 *
	*/
	function checkAuthentication() {
		global $foursquare;
	
		if (!isset($_SESSION['authtoken'])) {
			header("Location: logout.php");
		} else {
			$foursquare->SetAccessToken($_SESSION["authtoken"]);
			if (!isset($_SESSION['authenticated']) || 
					$_SESSION['authenticated'] !== date("Y-m-d")) {
				if (!checkUser()) {
					header("Location: logout.php");
				}
			}
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
			//TODO: if token is invalid, redirect to login/out
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
			
			$_SESSION['authenticated'] = date("Y-m-d");
			$_SESSION['userid'] = $userID;
			
			return true;
		}
		
	}
	
	
	/*
	 *
	 *	draw the page
	 *
	*/
	require_once("template/header.tpl.php");
	echo "<a href=\"mysqlfunctionstest.php\">mysqlfunctionstest</a>";
	require_once("template/cityview.tpl.php");
	require_once("template/citymenu.tpl.php");
	require_once("template/footer.tpl.php");
?>