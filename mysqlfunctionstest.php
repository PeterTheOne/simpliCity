<?php
session_start();

require_once("includes/essentials.inc.php");
require_once("includes/fs_essentials.inc.php");
require_once("includes/database.inc.php");
require_once("User.class.php");
	
fs_setup($_SESSION["authtoken"]);

echo "<h1>mysqlfunctionstest</h1>";

//TODO: put user in SESSION??
$user = getUser($_SESSION['userid']);

if (isset($_GET['place-citizen'])) {
	$venueId = sanitizeFilter($_GET['place-citizen']);
	$job = sanitizeFilter($_GET['job']);
	if (placeCitizen($venueId, $job)) {
		echo "<p>Citizen placed in $venueId with job: $job</p>";
	} else {
		echo "<p>error!</p>";
	}
} else if (isset($_GET['remove-citizen'])) {
	$venueId = sanitizeFilter($_GET['remove-citizen']);
	$job = sanitizeFilter($_GET['job']);
	if (removeCitizen($venueId, $job)) {
		echo "<p>Citizen removed in $venueId with job: $job</p>";
	} else {
		echo "<p>error!</p>";
	}
}
printOptions();

function getUser($userid) {
	db_connect();
	$r = mysql_query("SELECT * FROM users WHERE ID='$userid'");
	if (mysql_num_rows($r) !== 1) {
		db_disconnect();
		return false;
	}
	while ($line = mysql_fetch_array($r)) {
		db_disconnect();
		return new User($line);
	}
}

function placeCitizen($venueId, $job) {
	global $user;
	
	if ($user->unusedCitizen < 1) {
		return false;
	}
	db_connect();
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	$citizen = $user->unusedCitizen - 1;
	$r1 = mysql_query("UPDATE users SET UnusedCitizen='$citizen' WHERE ID='$user->id'");
	$r2 = mysql_query("INSERT INTO citizen (UserID,VenueID,Job) VALUES ('$user->id','$venueId','$job')");
	if ($r1 && $r2) {
		mysql_query("COMMIT");
		db_disconnect();
		$user->unusedCitizen--;
		return true;
	} else {
		mysql_query("ROLLBACK");
		db_disconnect();
		return false;
	}
}

function removeCitizen($venueId, $job) {
	global $user;
	
	db_connect();
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	$r1 = mysql_query("SELECT * FROM citizen WHERE UserID='$user->id' AND VenueID='$venueId' AND Job='$job' LIMIT 1");
	$line = mysql_fetch_array($r1);
	$removeId = $line['ID'];
	$r2 = mysql_query("DELETE FROM citizen WHERE ID='$removeId'");
	$citizen = $user->unusedCitizen + 1;
	$r3 = mysql_query("UPDATE users SET UnusedCitizen='$citizen' WHERE ID='$user->id'");
	if ($r1 && $r2 && $r3) {
		mysql_query("COMMIT");
		db_disconnect();
		$user->unusedCitizen++;
		return true;
	} else {
		mysql_query("ROLLBACK");
		db_disconnect();
		return false;
	}
}

function printOptions() {
	global $foursquare;
	global $user;

	echo "<h2>Options</h2>";
	
	$request = $foursquare->GetPrivate(
					"users/self/checkins", 
					array('limit' => 1)
	);
	$details = json_decode($request, false);
	
	$err = isset($details->meta->code)? $details->meta->code : 0;
	if($err >= 400 && $err <= 500){
		echo $err;
		echo "<p>".$details->meta->errorType.": ".$details->meta->errorDetail."</p>";
		return false;
	}
	
	$checkin = $details->response->checkins->items[0];
	$venue = $checkin->venue;
	echo "<p>Du hast am " . date("d.m.Y G:i", $checkin->createdAt);
	echo " in Foursquare im " . $venue->name . " eingecheckt.</p>";
	
	if ($checkin->createdAt < time() - 3600) {
		echo "<p>Du kannst keine Bürger plazieren, dein checkin ist zu lange her.</p>";
		//TODO: get venues nearby with venues/explore and geolocation
	} else if ($user->unusedCitizen < 1) {
		$job = "test";
		echo "<p>Du hast keine Bürger mehr die du plazieren kannst.</p>";
		echo "<p><a href=\"mysqlfunctionstest.php?remove-citizen=$venue->id&job=$job\">Bürger aus Stadt nehmen</a>?</p>";
	} else {
		$job = "test";
		echo "<p><a href=\"mysqlfunctionstest.php?place-citizen=$venue->id&job=$job\">Bürger plazieren</a>?</p>";
		echo "<p><a href=\"mysqlfunctionstest.php?remove-citizen=$venue->id&job=$job\">Bürger aus Stadt nehmen</a>?</p>";
		
		db_connect();
		$r = mysql_query("SELECT * FROM citizen WHERE UserID='$user->id' AND VenueID='$venue->id'");
		if (!$r) {
			echo "error: " . mysql_error();
		} else if (mysql_num_rows($r) > 0) {
			echo "<p>Liste von Bürgern: </p>";
			echo "<ul>";
			while ($line = mysql_fetch_array($r)) {
				echo "<li>";
				echo "Buerger: id: " . $line['ID'] . ", job: " . $line['Job'];
				echo "</li>";
			}
			echo "</ul>";
		}
		db_disconnect();
	}
	return true;
}

?>
