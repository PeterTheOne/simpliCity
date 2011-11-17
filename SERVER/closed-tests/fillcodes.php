<?php

require_once("includes/db_essentials.inc.php");
require_once("includes/essentials.inc.php");

$codes = array();
for($i = 0; $i < 50; $i++){
	$codes[$i] = hash("crc32b", date("YmdHis").$i);
}

db_connect();
	foreach ($codes as $code) {
		$r = mysql_query(
			"INSERT INTO codes (
				Name
			) VALUES (
				'$code'
			)"
		);
		db_hasErrors($r);
	}
db_disconnect();

?>