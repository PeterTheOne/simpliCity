<?php

require_once("includes/config.inc.php");
require_once("includes/lib/FoursquareAPI.class.php");

/*
 *
 *	use this to sanitize $_POST and $_GET input
 *
*/
function sanitizeFilter($str) {
	return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

/*
 *
 *	prints arrays nicely
 *
*/
function printarray($arr) {
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}

?>
