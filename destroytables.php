<?php

require_once("includes/database.inc.php");

if(isset($_GET["confirm"]) && $_GET["confirm"] == "true"){
	db_connect();

	mysql_query(
		"DROP TABLE IF EXISTS users;"
	);

	mysql_query(
		"DROP TABLE IF EXISTS citizen;"
	);

	db_disconnect();
} else {

	require_once("template/header.tpl.php");
	echo "<a href=\"?confirm=true\">Tabellen und ihren Inhalt wirklich l&ouml;schen!</a>";
	require_once("template/footer.tpl.php");
	
}
?>