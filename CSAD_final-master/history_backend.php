<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "includes/functions.inc.php";
require_once "includes/dbh.inc.php";

if (!isset($_SESSION['userId'])) {
    header("Location: error.php"); // Redirect to error page
    exit();
}
$userId = $_SESSION["userId"];

$orders = getOrdersByUserId($conn, $userId);

$history_table = [];
for($i = 0; $i < count($orders); $i++) {
    $stockShortName = $orders[$i]['stockShortName'];
    $temp = [
        "Logo" => '<img src="images/' . $stockShortName . '.png" alt="' . $stockShortName . '" class="company-logo">',
        "Company" => $stockShortName,
        "company_full_name" => $orders[$i]['stockLongName'],
        "Price" => $orders[$i]['price'], 
        "Quantity"=> $orders[$i]['quantity'], 
        "Action" => $orders[$i]['orderActionName'], 
        "Order Placed"=>$orders[$i]['orderPlaced']
    ];
    array_push($history_table, $temp);
}

// generateHistoryTable($history_table);