<?php
session_start();

require_once("includes/config.inc.php");

require_once("includes/essentials.inc.php");
require_once("includes/fs_essentials.inc.php");
require_once("includes/db_essentials.inc.php");

// fetch from fs
fs_setup($_SESSION['authtoken']);
$latestCheckin = fs_getSelfCheckinOne();
$venue = $latestCheckin->venue;

// fetch from db
//TODO: error handling
db_selectUser($_SESSION['userid']);
db_selectCitizenOfVenue($_SESSION['userid'], $venue->id);

echo "<script type=\"text/javascript\" src=\"script/citizenMenu.js\"></script>";
echo "<div style=\"padding-top:50px\">";
//TODO: remove '* 1000' it is only for debug
if (!fs_isCheckedIn($latestCheckin->createdAt)) {
	echo "<p>Du kannst keine Bürger plazieren oder entfernen, dein checkin ist zu lange her.</p>";
} else {
	//TODO: add job selection
	$job = "test";
	if ($user->unusedCitizen < 1) {
		echo "<p>Du hast keine Bürger mehr die du plazieren kannst.</p>";
	} else {
		echo "<p id=\"addCitizen\">Bürger plazieren?</p>";
	}
	if (count($citizenOfVenue) > 0) { //TODO: cout per job
		echo "<p id=\"removeCitizen\">Bürger aus Stadt nehmen?</p>";
	} else {
		echo "<p>Es gibt keine Bürger die du aus der Stadt nehmen kannst.</p>";
	}
}
echo "</div>";
?>
