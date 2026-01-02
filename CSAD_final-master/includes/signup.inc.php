<?php

/*
This if statement is to prevent the user from entering into the signup.inc.php
without doing anything with the form (Security Purpose)
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password_confirm = $_POST["password_confirm"];


    require_once 'dbh.inc.php'; 
    require_once 'functions.inc.php';

    //If there is the username or email already exists, go back to the page with the submitted data except password data
    if (uidExists($conn, $username, $email) !== false) {
        header("location: ../signInSignUp.php?error=userNameTaken&username=$username&email=$email");
        exit();
    }

    createUser($conn, $username, $email, $password);
    // header("location: ../signInSignUp.php");

    header('Location: ../loadr.php');
    exit();
} else {
    header("location: ../index.php?error=accessforbidden"); //database failed

}