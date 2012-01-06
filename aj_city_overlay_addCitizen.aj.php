<?php
session_start();

require_once("includes/essentials.inc.php");
require_once("includes/essentials_fs.inc.php");
require_once("includes/essentials_db.inc.php");
require_once("includes/User.class.inc.php");

// fetch from fs
fs_setup($_SESSION['authtoken']);
$latestCheckin = fs_getSelfCheckinOne();
$venue = $latestCheckin->venue;

// fetch from db
//TODO: error handling
db_selectUser($_SESSION['userid']);
$citizenGroupJob = db_selectCitizenOfVenue($_SESSION['userid'], $venue->id);

// addCitizen
$job = sanitize($_POST['id']);
$result = addCitizen($user, $venue->id, $latestCheckin->createdAt, $job);

echo "<div style=\"padding-top:50px\">";
if ($result) {
	echo "<p>added</p>";
} else {
	echo "<p>could not add</p>";
}
echo "</div>";

function addCitizen($user, $venueId, $checkinTime, $job) {
	if ($job == 0) {
		return false;
	}
	if ($user->unusedCitizen < 1) {
		return false;
	}
	if (!fs_isCheckedIn($checkinTime)) {
		return false;
	}
	db_connect();
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	$r1 = mysql_query("UPDATE users SET UnusedCitizen=UnusedCitizen-1 WHERE ID='$user->id'");
	$r2 = mysql_query("INSERT INTO citizen (UserID,VenueID,Job) VALUES ('$user->id','$venueId','$job')");
	if (db_hasErrors($r1) || db_hasErrors($r2)) {
		mysql_query("ROLLBACK");
		db_disconnect();
		return false;
	} else {
		mysql_query("COMMIT");
		db_disconnect();
		return true;
	}
}

?>