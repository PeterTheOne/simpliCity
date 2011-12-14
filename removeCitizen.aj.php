<?php
session_start();

require_once("includes/essentials.inc.php");
require_once("includes/fs_essentials.inc.php");
require_once("includes/db_essentials.inc.php");
require_once("User.class.php");

// fetch from fs
fs_setup($_SESSION['authtoken']);
$latestCheckin = fs_getSelfCheckinOne();
$venue = $latestCheckin->venue;

// fetch from db
//TODO: error handling
db_selectUser($_SESSION['userid']);

// removeCitizen 
$job = sanitize($_POST['id']);
$result = removeCitizen($venue->id, $latestCheckin->createdAt,  $job);

echo "<div style=\"padding-top:50px\">";
if ($result) {
	echo "<p>removed</p>";
} else {
	echo "<p>could not remove</p>";
}
echo "</div>";

function removeCitizen($venueId, $checkinTime, $job) {
	global $user;
	
	if ($job == 0) {
		return false;
	}
	if (!fs_isCheckedIn($checkinTime)) {
		return false;
	}
	
	db_connect();
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	$r1 = mysql_query("SELECT * FROM citizen WHERE UserID='$user->id' AND VenueID='$venueId' AND Job='$job' LIMIT 1");
	$line = mysql_fetch_array($r1);
	$removeId = $line['ID'];
	$r2 = mysql_query("DELETE FROM citizen WHERE ID='$removeId'");
	$r3 = mysql_query("UPDATE users SET UnusedCitizen=UnusedCitizen+1 WHERE ID='$user->id'");
	if (db_hasErrors($r1) || mysql_num_rows($r1) !== 1 || db_hasErrors($r2) || db_hasErrors($r3)) {
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