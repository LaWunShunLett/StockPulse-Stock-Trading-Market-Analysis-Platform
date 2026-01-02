<?php
require_once 'includes/dbh.inc.php'; 

header('Content-Type: application/json');

session_start(); // Start the session
if (!isset($_SESSION['userId'])) {
    header("Location: error.php"); // Redirect to error page
    exit();
}

if (isset($_GET['q'])) {
    $searchTerm = $_GET['q'];
    $searchTerm = "%" . $searchTerm . "%";

    $sql = "SELECT stockShortName, stockLongName FROM stocks WHERE stockShortName LIKE ? OR stockLongName LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }

    echo json_encode($suggestions);
}
?>