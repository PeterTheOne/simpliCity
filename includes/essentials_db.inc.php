<?php

require_once("includes/User.class.inc.php");

function db_connect() {
	$dbname="simplicity";
	//$dbhost="SQL09.FREEMYSQL.NET";
	$dbhost="localhost";
	$dbuser="anderl89";
	$dbpass="simpliCity";
	/*$dbhost = "mysql2.000webhost.com";
	$dbname = "a3943217_simplic";
	$dbuser = "a3943217_simplic";
	$dbpass = "hoooray11";*/
	$r = mysql_connect($dbhost,$dbuser,$dbpass);
	db_hasErrors($r);
	$r = mysql_select_db($dbname);
	db_hasErrors($r);
}

function db_disconnect(){
	$r = mysql_close();
	db_hasErrors($r);
}

/*
 *
 *	checks the errorCode and optionally printsErrors
 *
*/
function db_hasErrors($r) {
	if(!$r){
		if (PRINT_DB_ERRORS) {
			echo "<p>error: " . mysql_error() . "</p>";
		}
		return true;
	}
	return false;
}

/*
 *
 *	creates global user object
 *	don't forget to use db_selectUser after db changes!!!
 *
*/
function db_selectUser($userid) {
	global $user;
	db_connect();
	$r = mysql_query("SELECT * FROM users WHERE ID='$userid'");
	if (db_hasErrors($r) || mysql_num_rows($r) !== 1) {
		db_disconnect();
		return false;
	}
	while ($line = mysql_fetch_array($r)) {
		db_disconnect();
		$user = new User($line);
		return true;
	}
}

/*
 *
 *	creates global citizenOfVenue object
 *	don't forget to use db_selectCitizenOfVenue after db changes!!!
 *
*/
function db_selectCitizenOfVenue($userId, $venueId) {
	global $citizenOfVenue;
	db_connect();
	$r = mysql_query("SELECT * FROM citizen WHERE UserID='$userId' AND VenueID='$venueId'");
	if (db_hasErrors($r)) {
		db_disconnect();
		return false;
	}
	$citizenOfVenue = array();
	while ($line = mysql_fetch_array($r)) {
		$citizenOfVenue[] = $line;
	}
	db_disconnect();
	return true;
}

/*
 *
 *	creates global citizenOfVenue object
 *	don't forget to use db_selectCitizenOfVenue after db changes!!!
 *
*/
function db_citizenGroupJob($userId, $venueId) {
	db_connect();
	$r = mysql_query("
		SELECT jobs.*, 
			(SELECT COUNT(*) FROM citizen WHERE citizen.UserID='$userId' AND citizen.VenueID='$venueId' AND citizen.Job=jobs.ID) AS jobCount
		FROM jobs
	");
	if (db_hasErrors($r)) {
		db_disconnect();
		return false;
	}
	$citizenGroupJob = array();
	while ($line = mysql_fetch_array($r)) {
		$citizenGroupJob[] = $line;
	}
	db_disconnect();
	return $citizenGroupJob;
}

function db_citizenInVenue($venueId) {
	db_connect();
	$r = mysql_query("
		SELECT jobs.*, 
			(SELECT COUNT(*) FROM citizen WHERE VenueID='$venueId' AND citizen.Job=jobs.ID) AS jobCount
		FROM jobs
	");
	if (db_hasErrors($r)) {
		db_disconnect();
		return false;
	}
	$citizenGroupJob = array();
	while ($line = mysql_fetch_array($r)) {
		$citizenGroupJob[$line["ID"]] = $line["jobCount"];
	}
	db_disconnect();
	return $citizenGroupJob;
}

function db_playersInVenue($venueId) {
	db_connect();
	$r = mysql_query("SELECT COUNT(DISTINCT UserID) FROM citizen WHERE VenueID='$venueId'");
	if (db_hasErrors($r)) {
		db_disconnect();
		return false;
	}
	$citizenGroupJob = array();
	while ($line = mysql_fetch_array($r)) {
		db_disconnect();
		return $line["COUNT(DISTINCT UserID)"];
	}
	db_disconnect();
}

?>
