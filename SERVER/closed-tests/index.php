<?php
	session_start();
	
	if(isset($_GET["code"])){
		$_SESSION["code"] = $_GET["code"];
	}
	
	require_once("includes/config.inc.php");
	
	require_once("includes/lib/FoursquareAPI.class.php");
	require_once("includes/essentials.inc.php");
	require_once("includes/fs_essentials.inc.php");
	require_once("includes/db_essentials.inc.php");
	require_once("User.class.php");
	
	fs_setup($_SESSION["authtoken"]);
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
			header("Location: login.php");
		} else {
			$foursquare->SetAccessToken($_SESSION["authtoken"]);
			if (!isset($_SESSION['authenticated']) || 
					$_SESSION['authenticated'] !== date("Y-m-d")) {
				if (checkUser()) {
					$_SESSION['authenticated'] = date("Y-m-d");
				} else {
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
				
					$codecheck = mysql_query(
						"SELECT Name FROM codes WHERE Name='".$_SESSION["code"]."'"
					);
					$codeexists = false;
					while ($codeline = mysql_fetch_array($codecheck)){
						$codeexists = true;
					}
					
					if(!$codeexists){
						db_disconnect();
						return false;
					} else {
						mysql_query(
							"DELETE FROM codes WHERE Name='".$_SESSION["code"]."'"
						);
					}
					
					mysql_query(
						"INSERT INTO users (ID,Token,LoginDate,UnusedCitizen) VALUES ('$userID','$token','$time','$citizen')"
					);
				}
				
			} else {
				
				db_disconnect();
				return false;
			
			}
			
			db_disconnect();
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
	require_once("template/cityview.tpl.php");
	require_once("template/citymenu.tpl.php");
	require_once("template/footer.tpl.php");
?>