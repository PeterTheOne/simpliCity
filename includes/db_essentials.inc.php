<?php

require_once("includes/database.inc.php");
require_once("User.class.php");

/*
 *
 *	creates global user object
 *	don't forget to use db_selectUser after db changes!!!
 *
*/
function db_selectUser($userid) {
	global $user;
	db_connect();
	$r = mysql_query("SELECT * FROM users WHERE ID='$userid'");
	if (mysql_num_rows($r) !== 1) {
		db_disconnect();
		return false;
	}
	while ($line = mysql_fetch_array($r)) {
		db_disconnect();
		$user = new User($line);
		return true;
	}
}

?>
