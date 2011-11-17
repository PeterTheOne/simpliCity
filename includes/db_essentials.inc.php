<?php

require_once("User.class.php");

function db_connect() {
	$dbname="simplicity";
	$dbhost="SQL09.FREEMYSQL.NET";
	$dbuser="anderl89";
	$dbpass="simpliCity";
	mysql_connect($dbhost,$dbuser,$dbpass);
	mysql_select_db($dbname);
}

function db_disconnect(){
	mysql_close();
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
			(SELECT COUNT(*) FROM citizen WHERE citizen.Job=jobs.ID) AS jobCount
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
	$r = mysql_query("SELECT Job, COUNT(ID) FROM citizen WHERE VenueID='$venueId' GROUP BY Job");
	if (db_hasErrors($r)) {
		db_disconnect();
		return false;
	}
	$citizenGroupJob = array();
	while ($line = mysql_fetch_array($r)) {
		$citizenGroupJob[$line['Job']] = $line['COUNT(ID)'];
	}
	db_disconnect();
	return $citizenGroupJob;
}

function db_playersInVenue($venueId) {
	db_connect();
	$r = mysql_query("SELECT COUNT(UserID) FROM citizen WHERE VenueID='$venueId' GROUP BY UserID");
	if (db_hasErrors($r)) {
		db_disconnect();
		return false;
	}
	$citizenGroupJob = array();
	while ($line = mysql_fetch_array($r)) {
	
		$playersInVenue[] = $line['COUNT(UserID)'];
	}
	db_disconnect();
	return $playersInVenue;
}

?>
