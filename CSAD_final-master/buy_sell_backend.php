<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$userId = $_SESSION["userId"];


require_once 'includes/dbh.inc.php'; 
require_once "includes/functions.inc.php";
require_once "APIFetch/callAPI.php"; 

$stockShortName = isset($_GET['company']) ? urldecode($_GET['company']) : null;


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $quantity = (int)$_POST["quantity"];

    $orderAction = $_POST["action"];

    $quantity = (int)$quantity;

    $jsonData = callAPI($stockShortName); //fetch API to get the latestPrice
    $priceData = extractPriceData($jsonData); 
    $currentPrice = $priceData['currentPrice'];
    $currentPrice = round(floatval($currentPrice), 2);
    
    

    // var_dump($quantity);
    // var_dump($orderAction);
    // var_dump($stockShortName);
    // var_dump($currentPrice);
   
    $stockId = getStockIdByShortName($conn, $stockShortName);
    $result;
    if ($orderAction === "Buy") {
        $result = buyStock($conn, $userId, $stockId, $quantity, 1, $currentPrice);
    } else if ($orderAction === "Sell") {
        $result =  sellStock($conn, $userId, $stockId, $quantity, 2, $currentPrice);
    }

    if ($result['status'] === 'error') {
        $message = $result['message'];
        header("Location: buy_sell.php?company=$stockShortName&error=$message");
        exit();
    }
    // header("Location: buy_sell_backend.php?company=$stockShortName&currentPrice=$currentPrice");
    header("Location: main.php");
    exit();
    
} else {
    header("location: error.php");
    exit();
}