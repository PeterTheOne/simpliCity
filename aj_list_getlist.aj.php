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

if(isset($_POST["lat"]) && isset($_POST["lon"])){
	$venues = fs_getVenuesSearch($_POST["lat"].", ".$_POST["lon"], 10);
	echo "<h3>Checkin:</h3>";
	foreach ($venues as $venue) {?>
			<p class="widebutton"><a class="venue" href="index.php?view=checkin&checkinid=<?php echo $venue->id ?>"><?php echo $venue->name; ?></a></p>
	<?php }
} else {
	echo "<p>No geolocation data available, please check-in manually at foursquare!</p>";
}
?>