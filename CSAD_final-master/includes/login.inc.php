<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
This if statement is to prevent the user from entering into the signup.inc.php
without doing anything with the form (Security Purpose)
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["usernameSignIn"];
    $password = $_POST["passwordSignIn"];

    require_once 'dbh.inc.php'; 
    require_once 'functions.inc.php';

    loginUser($conn, $username, $password);
} else {
    header("location: ../logInTesting.php?error=accessforbidden");
    exit();
}

