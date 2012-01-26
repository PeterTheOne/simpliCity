<?php

require_once("includes/config.inc.php");
require_once("includes/User.class.inc.php");

function db_connect() {
	$r = mysql_connect(
		DB_HOST, 
		DB_USER, 
		DB_PASS);
	db_hasErrors($r);
	$r = mysql_select_db(DB_NAME);
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
function db_selectUser($userId) {
	global $user;
	db_connect();
	$userId = mysql_real_escape_string($userId);
	$r = mysql_query("SELECT * FROM users WHERE ID='$userId'");
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
	$userId = mysql_real_escape_string($userId);
	$venueId = mysql_real_escape_string($venueId);
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
 *	...
 *
*/
function db_citizenGroupJob($userId, $venueId) {
	db_connect();
	$userId = mysql_real_escape_string($userId);
	$venueId = mysql_real_escape_string($venueId);
	$r = mysql_query("
		SELECT 
			jobs.*, 
			(
				SELECT 
					COUNT(*) 
				FROM 
					citizen 
				WHERE 
					citizen.UserID='$userId' 
				AND 
					citizen.VenueID='$venueId' 
				AND 
					citizen.Job=jobs.ID
			) AS jobCount
		FROM 
			jobs
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

function db_citizenGroupJobByUser($userId) {
	db_connect();
	$userId = mysql_real_escape_string($userId);
	$r = mysql_query("
		SELECT 
			jobs.*, 
			(
				SELECT 
					COUNT(*) 
				FROM 
					citizen 
				WHERE 
					citizen.UserID='$userId' 
				AND 
					citizen.Job=jobs.ID
			) AS jobCount
		FROM 
			jobs
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
	$venueId = mysql_real_escape_string($venueId);
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
	$venueId = mysql_real_escape_string($venueId);
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

function db_countPlayerCities($userId) {
	db_connect();
	$userId = mysql_real_escape_string($userId);
	$r = mysql_query("
		SELECT 
			COUNT(DISTINCT VenueID) 
		AS 
			numCities 
		FROM 
			citizen 
		WHERE 
			UserID='$userId'
	");
	if (db_hasErrors($r)) {
		db_disconnect();
		return false;
	}
	while ($line = mysql_fetch_array($r)) {
		db_disconnect();
		return $line['numCities'];
	}
	db_disconnect();
}

function db_countMostCitizenCities($userId) {
	db_connect();
	$userId = mysql_real_escape_string($userId);
		$r = mysql_query("
		SELECT
			COUNT(DISTINCT userVenues.VenueID) AS cityCount
		FROM 
			(
				SELECT
					citizen.UserID, 
					citizen.VenueID, 
					COUNT(Job) AS countJob
				FROM 
					citizen
				WHERE
					UserID = '$userId'
				GROUP BY 
					VenueID
			) AS userVenues, 
			(
				SELECT
					citizen.UserID, 
					citizen.VenueID, 
					COUNT(Job) AS countJob
				FROM 
					citizen
				WHERE
					UserID <> '$userId'
				GROUP BY 
					UserID, 
					VenueID
			) AS nonUserVenues
		WHERE 
			userVenues.VenueID = nonUserVenues.VenueID
		AND 
			userVenues.countJob > nonUserVenues.countJob
	");
	if (db_hasErrors($r)) {
		db_disconnect();
		return false;
	}
	while ($line = mysql_fetch_array($r)) {
		db_disconnect();
		return $line['cityCount'];
	}
	db_disconnect();
}

function db_mostCitizenCities($userId, $limit = 10) {
	db_connect();
	$userId = mysql_real_escape_string($userId);
	$r = mysql_query("
		SELECT
			userVenues.VenueID, 
			userVenues.countJob
		FROM 
			(
				SELECT
					citizen.UserID, 
					citizen.VenueID, 
					COUNT(Job) AS countJob
				FROM 
					citizen
				WHERE
					UserID = '$userId'
				GROUP BY 
					VenueID
			) AS userVenues, 
			(
				SELECT
					citizen.UserID, 
					citizen.VenueID, 
					COUNT(Job) AS countJob
				FROM 
					citizen
				WHERE
					UserID <> '$userId'
				GROUP BY 
					UserID, 
					VenueID
			) AS nonUserVenues
		WHERE 
			userVenues.VenueID = nonUserVenues.VenueID
		AND 
			userVenues.countJob > nonUserVenues.countJob
		ORDER BY
			countJob DESC
		LIMIT 
			$limit
	");
	if (db_hasErrors($r)) {
		db_disconnect();
		return false;
	}
	$array = array();
	while ($line = mysql_fetch_array($r)) {
		$array[] = $line;
	}
	db_disconnect();
	return $array;
}

function db_citiesByUser($userId, $limit = 10) {
	db_connect();
	$userId = mysql_real_escape_string($userId);
	$r = mysql_query("
		SELECT
			citizen.UserID, 
			citizen.VenueID, 
			COUNT(Job) AS countJob
		FROM 
			citizen
		WHERE
			UserID = '$userId'
		GROUP BY 
			VenueID
		ORDER BY
			countJob DESC
		LIMIT 
			$limit
	");
	if (db_hasErrors($r)) {
		db_disconnect();
		return false;
	}
	$array = array();
	while ($line = mysql_fetch_array($r)) {
		$array[] = $line;
	}
	db_disconnect();
	return $array;
}

function db_citizenCountPerVenue() {
	db_connect();
	$r = mysql_query("
		SELECT
			citizen.VenueID, 
			COUNT(Job) AS countJob
		FROM 
			citizen
		GROUP BY 
			VenueID
		ORDER BY
			countJob DESC
	");
	if (db_hasErrors($r)) {
		db_disconnect();
		return false;
	}
	$array = array();
	while ($line = mysql_fetch_array($r)) {
		$array[] = $line;
	}
	db_disconnect();
	return $array;
}


?>
