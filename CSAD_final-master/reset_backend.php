<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/dbh.inc.php'; 
require_once "includes/functions.inc.php";  


session_start();
$userId = $_SESSION["userId"];
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    resetUserAccount($conn, $userId); 
    header("location: main.php?message='Reset Successful'");
    //exit();
} else {
    header("location: error.php");
    exit();
}