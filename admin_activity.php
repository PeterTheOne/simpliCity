<?php
	session_start();
	
	require_once("includes/config.inc.php");
	
	require_once("includes/lib/FoursquareAPI.class.php");
	require_once("includes/essentials.inc.php");
	require_once("includes/essentials_fs.inc.php");
	require_once("includes/essentials_db.inc.php");
	require_once("includes/User.class.inc.php");
	
	require_once("includes/functions_index.inc.php");
	
	checkAuthentication();
	
	if (!isset($_GET['pwd']) || sanitize($_GET['pwd']) !== ADMIN_PWD) {
		echo "<p>sry, no permission.</p>";
		exit();
	}
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>SimpliCity - activity</title>
		
		<meta charset="utf-8" />
		<!--<link rel="stylesheet" type="text/css" href="" />-->
	</head>
	<body>
		<h1>SimpliCity - activity</h1>
		<ul>
<?php
	$activity = db_citizenActivity(20);
	
	foreach ($activity as $line) {
		$user = $line['FirstName'] . " " . $line['LastName'];
		$userId = $line['UserID'];
		$citizenCount = $line['citizenCount'];
		//TODO: cache or save venue names to make request faster: 
		$venue = $line['VenueID'];//fs_getVenue($line['VenueID'])->name;
		$venueId = $line['VenueID'];
		if ($line['dateAddedUnix'] == 0) {
			$day = "Unknown";
		} else {
			$day = date("d.m.Y", $line['dateAddedUnix']);
		}
		echo "<li>";
		echo "$day: ";
		echo "<a href=\"https://foursquare.com/user/$userId\">$user</a> ";
		echo "has added $citizenCount citizen in ";
		echo "<a href=\"https://foursquare.com/v/$venueId\">$venue</a>";
		echo "</li>";
		//printarray($line);
	}
?>
		</ul>
	</body>
</head>