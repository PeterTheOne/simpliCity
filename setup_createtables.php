<?php

require_once("includes/essentials_db.inc.php");

db_connect();

$r = mysql_query(
	"CREATE TABLE IF NOT EXISTS users (
		ID BIGINT NOT NULL PRIMARY KEY,
		FirstName VARCHAR(128) NOT NULL,
		LastName VARCHAR(128) NOT NULL,
		Token VARCHAR(128) NOT NULL,
		LoginDate DATE NOT NULL,
		UnusedCitizen INT NOT NULL,
		Points INT NOT NULL DEFAULT '0'
	)ENGINE=InnoDB;");
db_hasErrors($r);

$r = mysql_query(
	"CREATE TABLE IF NOT EXISTS citizen (
		ID BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		UserID BIGINT NOT NULL,
		VenueID VARCHAR(128) NOT NULL,
		Job BIGINT NOT NULL
	)ENGINE=InnoDB;");
db_hasErrors($r);

$r = mysql_query(
	"CREATE TABLE IF NOT EXISTS jobs (
		ID BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		Name TINYTEXT NOT NULL,
		Description TEXT NOT NULL,
		Education TINYINT NOT NULL,
		Research TINYINT NOT NULL,
		Wealth TINYINT NOT NULL,
		Production TINYINT NOT NULL,
		Religion TINYINT NOT NULL
	)ENGINE=InnoDB;");
db_hasErrors($r);
	
db_disconnect();

?>