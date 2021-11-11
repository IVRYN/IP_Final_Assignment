<?php

DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASS', 'root');
DEFINE ('DB_HOST', 'mysql');
DEFINE ('DB_NAME', 'busservices');

$dbconnect  =   mysqli_connect (DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$dbconnect)
    die ("Could not connect to MySQL: " . mysqli_connect_error());

mysqli_set_charset ($dbconnect, 'utf8');
?>
