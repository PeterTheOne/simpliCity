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
			) AS jobCount,
			(
				SELECT 
					COUNT(*) 
				FROM 
					citizen 
				WHERE
					citizen.VenueID='$venueId' 
				AND 
					citizen.Job=jobs.ID
			) AS totalJobCount
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

function db_remainingCitizen($userId) {
	db_connect();
	$userId = mysql_real_escape_string($userId);
	$r = mysql_query("
		SELECT UnusedCitizen FROM users WHERE ID='$userId'
	");
	if (db_hasErrors($r)) {
		db_disconnect();
		return false;
	}
	$count = 0;
	while ($line = mysql_fetch_array($r)) {
		$count = $line["UnusedCitizen"];
	}
	db_disconnect();
	return $count;
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
			COUNT(*) AS cityCount
		FROM 
			(
				SELECT 
					* 
				FROM 
					(
						SELECT 
							UserID, 
							VenueID, 
							COUNT(*) AS countJob 
						FROM 
							citizen 
						GROUP BY 
							UserID, 
							VenueID 
						ORDER BY 
							VenueID, 
							countJob DESC 
					) AS subSubTable
				GROUP BY VenueID
			) AS subTable 
		WHERE 
			UserID = '$userId'
		ORDER BY 
			countJob DESC
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
			* 
		FROM 
			(
				SELECT 
					* 
				FROM 
					(
						SELECT 
							UserID, 
							VenueID, 
							COUNT(*) AS countJob 
						FROM 
							citizen 
						GROUP BY 
							UserID, 
							VenueID 
						ORDER BY 
							VenueID, 
							countJob DESC 
					) AS subSubTable
				GROUP BY VenueID
			) AS subTable 
		WHERE 
			UserID = '$userId'
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

function db_userAndCitizenCount($venueId) {
	db_connect();
	$venueId = mysql_real_escape_string($venueId);
	$r = mysql_query("
		SELECT 
			*, 
			COUNT(*) AS citizenCount 
		FROM 
			citizen 
		INNER JOIN 
			users 
		ON 
			citizen.UserID = users.ID
		WHERE 
			VenueID = '$venueId' 
		GROUP BY 
			UserID
		ORDER BY
			citizenCount DESC
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

function db_selectUsers() {
	db_connect();
	$r = mysql_query("
		SELECT
			*
		FROM 
			users
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

function db_getHighscores() {
	db_connect();
	$r = mysql_query("
		SELECT FirstName, LastName, Points FROM users ORDER BY Points DESC LIMIT 10
	");
	if (db_hasErrors($r)) {
		db_disconnect();
		return false;
	}
	$scores = array();
	while ($line = mysql_fetch_array($r)) {
		$scores[] = $line;
	}
	db_disconnect();
	return $scores;
}

function db_getUserScorePosition($userId) {
	db_connect();
	$userId = mysql_real_escape_string($userId);
	$r = mysql_query("
		SELECT Points FROM users WHERE ID='$userId'
	");
	if (db_hasErrors($r)) {
		db_disconnect();
		return false;
	}
	$points = 0;
	while ($line = mysql_fetch_array($r)) {
		$points = $line["Points"];
	}
	$r = mysql_query("
		SELECT COUNT(*) AS position FROM users WHERE Points > '$points'
	");
	if (db_hasErrors($r)) {
		db_disconnect();
		return false;
	}
	$position = 0;
	while ($line = mysql_fetch_array($r)) {
		$position = $line["position"];
	}
	db_disconnect();
	return $position+1;
}


?>
