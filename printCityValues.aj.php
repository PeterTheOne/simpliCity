<?php
require_once("includes/essentials.inc.php");
require_once("includes/db_essentials.inc.php");
require_once("includes/fs_essentials.inc.php");
if(!isset($_SESSION)){
	session_start();
	fs_setup($_SESSION["authtoken"]);
}
$self = fs_getSelfCheckinOne();
$venueID = $self->venue->id;
$venueName = $self->venue->name;
printCityValues(db_citizenInVenue($venueID),db_playersInVenue($venueID),$venueID, $venueName);
?>