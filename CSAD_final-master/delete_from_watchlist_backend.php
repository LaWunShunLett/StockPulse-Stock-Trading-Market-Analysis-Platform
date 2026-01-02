<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json'); 

session_start();
require_once 'includes/dbh.inc.php'; 
require_once 'includes/functions.inc.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION["userId"];
    $stockShortName = isset($_POST['stockShortName']) ? $_POST['stockShortName'] : '';

    if ($userId > 0 && !empty($stockShortName)) {
        $stockId = getStockIdByShortName($conn, $stockShortName);
        if ($stockId && deleteStockFromWatchlist($conn, $userId, $stockId)) {
            $response['success'] = true;
        } else {
            $response['message'] = 'Failed to delete stock from watchlist.';
        }
    } else {
        $response['message'] = 'Invalid user ID or stock short name.';
    }
} else {
    header("location: error.php");
    exit();
}

echo json_encode($response);
?>