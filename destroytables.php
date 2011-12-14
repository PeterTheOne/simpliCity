<?php

require_once("includes/db_essentials.inc.php");
require_once("includes/essentials.inc.php");

if(isset($_GET["confirm"]) && sanitize($_GET["confirm"]) == "true"){
	db_connect();

	$r = mysql_query(
		"DROP TABLE IF EXISTS users;"
	);
	db_hasErrors($r);

	$r = mysql_query(
		"DROP TABLE IF EXISTS citizen;"
	);
	db_hasErrors($r);

	$r = mysql_query(
		"DROP TABLE IF EXISTS jobs;"
	);
	db_hasErrors($r);

	db_disconnect();
} else {

	require_once("template/header.tpl.php");
	echo "<a href=\"?confirm=true\">Tabellen und ihren Inhalt wirklich l&ouml;schen!</a>";
	require_once("template/footer.tpl.php");
	
}
?>