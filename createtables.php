<?php

require_once("includes/database.inc.php");

db_connect();

mysql_query(
	"CREATE TABLE IF NOT EXISTS users (
		ID BIGINT NOT NULL PRIMARY KEY,
		Token VARCHAR(128) NOT NULL,
		LoginDate DATE NOT NULL,
		UnusedCitizen INT NOT NULL
	);"
);

mysql_query(
	"CREATE TABLE IF NOT EXISTS citizen (
		ID BIGINT NOT NULL PRIMARY KEY,
		UserID BIGINT NOT NULL,
		VenueID VARCHAR(128) NOT NULL,
		Job TINYTEXT NOT NULL
	);"
);

db_disconnect();

?>