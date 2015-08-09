<?php

// setup config

define("DEV_MODE", true);
define("PRINT_FS_ERRORS", true);
define("PRINT_DB_ERRORS", true);

// database config

define("DB_NAME", "");
define("DB_HOST", "");
define("DB_USER", "");
define("DB_PASS", "");

// Testserver zugang
/*
define("DB_NAME", "");
define("DB_HOST", "");
define("DB_USER", "");
define("DB_PASS", "");
*/

// get admin password

define("ADMIN_PWD", "");

// foursquare config
// see: https://developer.foursquare.com/docs/oauth.html
// for every uri a new CLIENT_ID must be created.
if (DEV_MODE) {
    // development config
    define("CLIENT_ID",		"");
    define("CLIENT_SECRET",	"");
    define("REDIRECT_URI", "");
} else {
    define("CLIENT_ID",		"");
    define("CLIENT_SECRET",	"");
    define("REDIRECT_URI", "");
}
define('FOURSQUARE_API_VERSION', '20120111');

// game preferences
define("CITIZEN_STARTUP", 5);	//citizen you get on startup
define("CITIZEN_INCREASE", 5);	//citizen you get on login per day

define("SEC_PER_HOUR", 3600);
define("CHECKIN_TIME", 2 * SEC_PER_HOUR);	//hours you can play after fs checkin

?>
