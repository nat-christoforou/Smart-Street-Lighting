<?php
// Info to connect to smart_street_light database

$MyUsername = "user_name";  // enter your username for mysql
$MyPassword = "password";  // enter your password for mysql
$MyHostname = "127.0.0.1";  // this is usually "localhost" unless your database resides on a different server

$dbh = mysqli_connect($MyHostname , $MyUsername, $MyPassword);
$selected = mysqli_select_db($dbh,"smart_street_light");
?>