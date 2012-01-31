<?php

require_once("includes/essentials_db.inc.php");
require_once("includes/essentials_fs.inc.php");

db_connect();
fs_setup();

$r = mysql_query("
	ALTER TABLE 
		users 
	ADD 
		FirstName VARCHAR( 128 ) NOT NULL AFTER ID , 
	ADD 
		LastName VARCHAR( 128 ) NOT NULL AFTER FirstName
");
echo "add FirstName and LastName fields<br />";
db_hasErrors($r);

echo "get users from table<br />";
$users = db_selectUsers();

foreach ($users as $db_user) {
	$userId = $db_user['ID'];
	$foursquare->SetAccessToken($db_user['Token']);
	$fs_user = fs_getUser($userId);
	$firstName = $fs_user->firstName;
	$lastName = $fs_user->lastName;
	
	db_connect();
	$r = mysql_query("
		UPDATE 
			users 
		SET 
			FirstName='$firstName', 
			LastName='$lastName' 
		WHERE 
			ID='$userId'
	");
	echo "update user: $firstName $lastName<br />";
	db_hasErrors($r);
	db_disconnect();
}


?>
