<?php
// This page allows the administator to change config,
// for the install of the application.

// Set farm location e.g. /var/www/html/farm
$farm = "/var/www/html/farm";
$mwadmin = "/var/www/html/mwadmin-main";

/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'mediawiki');
define('DB_PASSWORD', 'VeryStrongPassword');
define('DB_NAME', 'mwadmin');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
