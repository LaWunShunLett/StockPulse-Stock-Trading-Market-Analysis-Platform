<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "includes/functions.inc.php";
require_once "includes/dbh.inc.php";
require_once "APIFetch/callAPI.php";


if (!isset($_SESSION['userId'])) {
    header("Location: error.php"); // Redirect to error page
    exit();
}
$userId = $_SESSION["userId"];

$watchlist_items = getWatchlist($conn, $userId);

$watchlist_item_table =[];

for($i = 0; $i < count($watchlist_items); $i++) {


    $jsonData = callAPI($watchlist_items[$i]["stockShortName"]);
    $priceData = extractPriceData($jsonData);

    $currentPrice = $priceData['currentPrice']; 
    $percent = (floatval(str_replace("%", "", $priceData['priceChangePercent'])) / 100); //convert string value to number
    $change = ($currentPrice * $percent);

    $change_string = "";
    if ($percent >= 0) {
        $change_string .= "+" . number_format($change, 2) . " +" . $priceData['priceChangePercent'];
    } else {
        $change_string .= number_format($change, 2) . " ". $priceData['priceChangePercent'];
    }

    $temp = [
        "Logo" => '<img src="images/' . htmlspecialchars($watchlist_items[$i]["stockShortName"]) . '.png" alt="' . htmlspecialchars($watchlist_items[$i]["stockShortName"]) . '" class="company-logo">',
        "Company" => $watchlist_items[$i]["stockShortName"],
        "company_full_name" => $watchlist_items[$i]["stockLongName"],
        "Price" => $currentPrice,
        "Change" => $change_string
    ];

    array_push($watchlist_item_table, $temp);

}