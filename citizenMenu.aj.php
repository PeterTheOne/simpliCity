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

echo "<script type=\"text/javascript\" src=\"script/citizenMenu.js\"></script>";
echo "<div style=\"padding-top:50px\">";
//TODO: remove '* 1000' it is only for debug
if (!fs_isCheckedIn($latestCheckin->createdAt)) {
	echo "<p>Du kannst keine Bürger plazieren oder entfernen, dein checkin ist zu lange her.</p>";
} else {
	//db_selectCitizenOfVenue($_SESSION['userid'], $venue->id);
	db_citizenGroupJob($_SESSION['userid'], $venue->id);
	//TODO: auch jobs auflisten in denen keine bürger sind.
	//TODO: machen das der addCitizen button auch wirklich diesen job hinzufügt
	if (count($citizenGroupJob) < 1) {
		echo "<p>Es gibt keine Bürger in dieser Stadt</p>";
	} else {
		echo "<table border=\"1\">";
		echo "<tr><td>job</td><td>count</td><td>-</td><td>+</td></tr>";
		foreach ($citizenGroupJob as $count => $job) {
			echo "<tr>";
			echo "<td>" . $job . "</td>";
			echo "<td>" . $count . "</td>";
			echo "<td><span id=\"removeCitizen\">Bürger aus Stadt nehmen?</span></td>";
			echo "<td><span id=\"addCitizen\">Bürger plazieren?</span></td>";
			echo "</tr>";
		}
		echo "</table>";
	}
}
echo "</div>";

?>
