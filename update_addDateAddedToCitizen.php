<?php

require_once("includes/essentials_db.inc.php");

db_connect();

$r = mysql_query("
	ALTER TABLE 
		citizen 
	ADD 
		DateAdded TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER ID
");
echo "add DateAdded field to citizen table<br />";
db_hasErrors($r);

db_disconnect();

?>
