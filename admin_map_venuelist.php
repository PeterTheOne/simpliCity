<?php
	session_start();
	
	require_once("includes/config.inc.php");
	
	require_once("includes/lib/FoursquareAPI.class.php");
	require_once("includes/essentials.inc.php");
	require_once("includes/essentials_fs.inc.php");
	require_once("includes/essentials_db.inc.php");
	require_once("includes/User.class.inc.php");
	
	require_once("includes/functions_index.inc.php");
	
	fs_setup($_SESSION["authtoken"]);
	if(!isset($_SESSION['userid'])) {
		$_SESSION['userid'] = fs_getUserID();	
	}
	checkAuthentication();

echo "lat\tlon\ttitle\tdescription\ticon\ticonSize\ticonOffset\n";

	$venueList = db_citizenCountPerVenue();
	foreach ($venueList as $db_venue) {
		$fs_venue = fs_getVenue($db_venue['VenueID']);
		$venueUsers = db_userAndCitizenCount($db_venue['VenueID']);
		
		echo $fs_venue->location->lat . "\t";
		echo $fs_venue->location->lng . "\t";
		echo $fs_venue->name . "\t";
		echo "VenueID: " . $db_venue['VenueID'] . "<br />";
		echo "countJob: " . $db_venue['countJob'] . "<br />";
		echo "userlist: <ul>";
		
		foreach ($venueUsers as $venueUser) {
			echo "<li>" . $venueUser['UserID'] . ": " . $venueUser['citizenCount'] . "</li>";
		}
		
		echo "</ul>\t";
		echo "./images/map/marker.png\t21,25\t-10,-25\n";
		
	}
?>
