<?php
	
function db_connect() {
	/*$dbname="simplicity";
	$dbhost="SQL09.FREEMYSQL.NET";
	$dbuser="anderl89";
	$dbpass="simpliCity";*/
	$dbname="simplicity";
	$dbhost="localhost";
	$dbuser="root";
	$dbpass="Zautale";
	mysql_connect($dbhost,$dbuser,$dbpass);
	mysql_select_db($dbname);
}

function db_disconnect(){
	mysql_close();
}

?>