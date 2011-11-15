<?php

require_once("includes/config.inc.php");
require_once("includes/lib/FoursquareAPI.class.php");

function sanitizeFilter($str) {
	return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

function printarray($arr) {
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}

/*
 *
 *	setup the foursquare connection
 *	you still need to SetAccessToken!!!
 *
*/
function setupFoursquare(){
	global $foursquare;
	
	$foursquare = new FoursquareAPI(CLIENT_ID, CLIENT_SECRET);
}

?>