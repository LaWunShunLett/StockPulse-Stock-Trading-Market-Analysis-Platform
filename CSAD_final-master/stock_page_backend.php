<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['userId'])) {
    header("Location: error.php"); // Redirect to error page
    exit();
}

require_once "APIFetch/callAPI.php";
require_once 'includes/dbh.inc.php'; 
require_once "includes/functions.inc.php";

$companyId = getStockIdByShortName($conn, $company);

$jsonData = callAPI($company);

$financialMetrics = extractFinancialMetrics($jsonData);
$priceData = extractPriceData($jsonData);

$dayLow = $priceData['dayLow'];
$dayHigh = $priceData['dayHigh'];
$currentPrice = $priceData['currentPrice'];

$fiftyTwoWeekLow = $priceData['fiftyTwoWeekLow'];
$fiftyTwoWeekHigh = $priceData['fiftyTwoWeekHigh'];
$fiftyTwoWeekCurrent = $priceData['currentPrice'];

// Calculate percentage positions for sliders (day's range, 52 week's range)
$dayRangePercent = 0;
$fiftyTwoWeekRangePercent = 0;

if ($dayHigh > $dayLow) {
    $dayRangePercent = (($currentPrice - $dayLow) / ($dayHigh - $dayLow)) * 100;
}

if ($fiftyTwoWeekHigh > $fiftyTwoWeekLow) {
    $fiftyTwoWeekRangePercent = (($fiftyTwoWeekCurrent - $fiftyTwoWeekLow) / ($fiftyTwoWeekHigh - $fiftyTwoWeekLow)) * 100;
}

// Show data from stock recommendation
$recommendationTrends = extractRecommendationData($jsonData);

// Validate and sanitize the recommendation trends data
foreach ($recommendationTrends as $key => $value) {
    if ($value < 0) {
        $recommendationTrends[$key] = 0;
    } elseif ($value > 100) {
        $recommendationTrends[$key] = 100;
    }
    // echo $key . " : ".$value;
}

// Check if the stock already exists in the watchlist or not
$stockId = getStockIdByShortName($conn, $company);
$stock_exists = isStockInWatchlist($conn, $userId, $stockId);
$stock_description = getStockDescriptionById($conn, $stockId);