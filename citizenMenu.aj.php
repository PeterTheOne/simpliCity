<?php
session_start();

require_once("includes/config.inc.php");

require_once("includes/essentials.inc.php");
require_once("includes/fs_essentials.inc.php");
require_once("includes/db_essentials.inc.php");

// fetch from fs
fs_setup($_SESSION["authtoken"]);
$latestCheckin = fs_getSelfCheckinOne();
$venue = $latestCheckin->venue;

// fetch from db
db_selectUser($_SESSION['userid']);

echo "<script type=\"text/javascript\" src=\"script/citizenMenu.js\"></script>";
echo "<div style=\"padding-top:50px\">";
if ($latestCheckin->createdAt < time() - CHECKIN_TIME * 1000 * 3600) {
	echo "<p>Du kannst keine Bürger plazieren, dein checkin ist zu lange her.</p>";
} else if ($user->unusedCitizen < 1) {
	//TODO: add job selection
	$job = "test";
	echo "<p>Du hast keine Bürger mehr die du plazieren kannst.</p>";
	//TODO: removeCitizen nur anzeigen wenn welche drin sind..
	echo "<p id=\"removeCitizen\">Bürger aus Stadt nehmen?</p>";	
} else {
	//TODO: add job selection
	$job = "test";
	echo "<p id=\"addCitizen\">Bürger plazieren?</p>";
	//TODO: removeCitizen nur anzeigen wenn welche drin sind..
	echo "<p id=\"removeCitizen\">Bürger aus Stadt nehmen?</p>";
}
echo "</div>";
?>
