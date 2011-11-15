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

echo "<div style=\"padding-top:50px\">";
if ($latestCheckin->createdAt < time() - CHECKIN_TIME * 3600) {
	echo "<p>Du kannst keine Bürger plazieren, dein checkin ist zu lange her.</p>";
} else if ($user->unusedCitizen < 1) {
	$job = "test";
	echo "<p>Du hast keine Bürger mehr die du plazieren kannst.</p>";
	//TODO: change to aj "links"
	echo "<p><a href=\"mysqlfunctionstest.php?remove-citizen=$venue->id&job=$job\">Bürger aus Stadt nehmen</a>?</p>";	
} else {
	$job = "test";
	//TODO: change to aj "links"
	echo "<p><a href=\"mysqlfunctionstest.php?place-citizen=$venue->id&job=$job\">Bürger plazieren</a>?</p>";
	echo "<p><a href=\"mysqlfunctionstest.php?remove-citizen=$venue->id&job=$job\">Bürger aus Stadt nehmen</a>?</p>";
}
echo "</div>";
?>
