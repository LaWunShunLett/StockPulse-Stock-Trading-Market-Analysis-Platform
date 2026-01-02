<?php



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/dbh.inc.php'; 
require_once "includes/functions.inc.php";  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST["userId"];
    $stockName = $_POST["stockName"];

    
   $stockId = getStockIdByShortName($conn, $stockName);
    if (!isStockInWatchlist($conn,$userId, $stockId)) {
        addToWatchlist($conn, $userId, $stockId);
       
    }
   
    exit();
} else {
    header("location: error.php");
    exit();
}