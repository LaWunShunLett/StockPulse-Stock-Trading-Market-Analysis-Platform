<?php

//Crucial variable for connnecting with database
$serverName = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "stockPulse"; //database Name to look for in phpMyAdmin


$conn = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);

if (!$conn) {
    die("Connection failed: ".mysqli_connect_error());
}