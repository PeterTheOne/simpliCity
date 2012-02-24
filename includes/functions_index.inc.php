<?php
	/*
	 *
	 *	check if the user token has been checked
	 *	in this session and today
	 *	sets AccessToken
	 *
	*/
	function checkAuthentication() {
		global $foursquare;
		
		if (isset($_GET['code'])) {
			fs_setup();
			$_SESSION['authtoken'] = $foursquare->GetToken(sanitize($_GET['code']), REDIRECT_URI);
			header("Location: redir_login.php");
		}
	
		if (!isset($_SESSION['authtoken']) || 
				$_SESSION['authtoken'] === "") {
			header("Location: redir_logout.php");
		} else {
			fs_setup($_SESSION["authtoken"]);
			if(!isset($_SESSION['userid'])) {
				$_SESSION['userid'] = fs_getUserID();	
			}
			if (!isset($_SESSION['authenticated']) || 
					$_SESSION['authenticated'] !== date("Y-m-d")) {
				if (checkUser()) {
					$_SESSION['authenticated'] = date("Y-m-d");
				} else {
					header("Location: redir_logout.php");
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
		
		$userID = $_SESSION['userid'];
		$token = $_SESSION["authtoken"];
		$time = date("Y-m-d");
		$citizen = CITIZEN_STARTUP;
		
		db_connect();
		
		if($result = mysql_query(
			"SELECT * FROM users WHERE ID='$userID'"
		)){
			
			$fs_user = fs_getUser($userID);
			$firstName = $fs_user->firstName;
			$lastName = $fs_user->lastName;
			
			$exists = false;
			while ($line = mysql_fetch_array($result)){
				$exists = true;
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
						"UPDATE users SET $setToken $alterCitizen LoginDate='$time', FirstName='$firstName', LastName='$lastName' WHERE ID='$userID'"
					);
				} else {
					//echo "nothing changed";
				}
			}
			
			if(!$exists){
				mysql_query(
					"INSERT INTO users (ID,FirstName,LastName,Token,LoginDate,UnusedCitizen) VALUES ('$userID','$firstName','$lastName','$token','$time','$citizen')"
				);
			}
			
		} else {
		
			db_disconnect();
			return false;
		
		}
		
		db_disconnect();		
		return true;
		
	}	
?>