<?php
	
connect();
	
function connect() {
	$dbname="simpliCity";
	$dbhost="SQL09.FREEMYSQL.NET";
	$dbuser="anderl89";
	$dbpass="simpliCity";
	mysql_connect($dbhost,$dbuser,$dbpass);
	mysql_select_db($dbname);
}

function disconnect(){
	mysql_close();
}

?>