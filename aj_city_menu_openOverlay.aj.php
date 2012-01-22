<?php
session_start();

require_once("includes/config.inc.php");

require_once("includes/essentials.inc.php");
require_once("includes/essentials_fs.inc.php");
require_once("includes/essentials_db.inc.php");

// fetch from fs
fs_setup($_SESSION['authtoken']);
$latestCheckin = fs_getSelfCheckinOne();
$venue = $latestCheckin->venue;

// fetch from db
//TODO: error handling
db_selectUser($_SESSION['userid']);

echo "<div style=\"padding-top:50px\">";

displayMenu($latestCheckin);

echo "</div>";

function displayMenu($latestCheckin) {
	global $user;
	global $venue;

	if (!fs_isCheckedIn($latestCheckin->createdAt)) {
		echo "<p>Du kannst keine Bürger plazieren oder entfernen, dein checkin ist zu lange her.</p>";
	}
	$citizenGroupJob = db_citizenGroupJob($_SESSION['userid'], $venue->id);
	echo "<table border=\"1\">";
	echo "<tr><td>id</td><td>job</td><td>desc</td><td>count</td><td>-</td><td>+</td></tr>";
	foreach ($citizenGroupJob as $job) {
		echo "<tr class=\"jobentry\">";
		echo "<td>" . $job['Job'] . "</td>";
		echo "<td>" . $job['Name'] . "</td>";
		echo "<td>" . $job['Description'] . "</td>";
		echo "<td>" . $job['jobCount'] . "</td>";
		if ($job['jobCount'] > 0 && fs_isCheckedIn($latestCheckin->createdAt)) {
			//echo "<td><span class=\"id\" style=\"display: none\">". $job['ID'] ."</span><span class=\"removeCitizen\">Bürger aus Stadt nehmen?</span></td>";
			echo "<td>1</td>";
		} else {
			//echo "<td>-</td>";
			echo "<td>0</td>";
		}
		if ($user->unusedCitizen > 0 && fs_isCheckedIn($latestCheckin->createdAt)) {
			//echo "<td><span class=\"id\" style=\"display: none\">". $job['ID'] ."</span><span class=\"addCitizen\">Bürger plazieren?</span></td>";
			echo "<td>1</td>";
		} else {
			//echo "<td>-</td>";
			echo "<td>0</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
}

?>
