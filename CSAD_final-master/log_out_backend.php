<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    // Destroy session
    session_unset();
    session_destroy();

    // Redirect to signInSignUp page
    header("Location: signInSignUp.php");
    exit;
} else {
    // Redirect to profile page if accessed directly
    header("Location: error.php");
    exit;
}
?>