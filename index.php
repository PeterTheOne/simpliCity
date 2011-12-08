<?php
	session_start();
	
	require_once("includes/config.inc.php");
	
	require_once("includes/lib/FoursquareAPI.class.php");
	require_once("includes/essentials.inc.php");
	require_once("includes/fs_essentials.inc.php");
	require_once("includes/db_essentials.inc.php");
	require_once("User.class.php");
	
	fs_setup($_SESSION["authtoken"]);
	if(!isset($_SESSION['userid'])) {
		$_SESSION['userid'] = fs_getUserID();	
	}
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
	
	/*
	 *
	 *	draw the page
	 *
	*/
	require_once("template/header.tpl.php");
	if (fs_isCheckedIn(fs_getSelfCheckinOne()->createdAt)) {
		//TODO: wtf restructure...
		require_once("template/cityview.tpl.php");
	} else {
		//TODO: fix these include things
		if (isset($_GET['checkinid'])) {
			require_once("checkin.php");
		} else {
			require_once("venuelist.php");
		}
	}
	require_once("template/citymenu.tpl.php");
	require_once("template/footer.tpl.php");
?>
