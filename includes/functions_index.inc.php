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
	
		if (!isset($_SESSION['authtoken'])) {
			header("Location: redir_logout.php");
		} else {
			$foursquare->SetAccessToken($_SESSION["authtoken"]);
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
?>