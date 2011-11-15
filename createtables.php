<?php

require_once("includes/database.inc.php");

db_connect();

if(!mysql_query(
	"CREATE TABLE IF NOT EXISTS users (
		ID BIGINT NOT NULL PRIMARY KEY,
		Token VARCHAR(128) NOT NULL,
		LoginDate DATE NOT NULL,
		UnusedCitizen INT NOT NULL
	);"
)){
	echo "error!";
}

if(!mysql_query(
	"CREATE TABLE IF NOT EXISTS citizen (
		ID BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		UserID BIGINT NOT NULL,
		VenueID VARCHAR(128) NOT NULL,
		Job TINYTEXT NOT NULL
	);"
)){
	echo "error!";
}
db_disconnect();

?>