<?php

require_once("includes/db_essentials.inc.php");
require_once("includes/essentials.inc.php");

if(isset($_GET["pwd"]) && $_GET["pwd"] == "simpliCity"){
	db_connect();
	$result = mysql_query(
		"SELECT * FROM codes"
	);
	while ($line = mysql_fetch_array($result)){
		echo "<p>http://simplicity.net76.net/index.php?code=".$line["Name"]."</p>";
	}
	db_disconnect();
}
?>