<?php

require_once("includes/essentials_db.inc.php");

db_connect();

$r = mysql_query("
	ALTER TABLE 
		users 
	ADD 
		Points INT NOT NULL DEFAULT '0' 
");
echo "add Points field<br />";
db_hasErrors($r);

db_disconnect();

?>
