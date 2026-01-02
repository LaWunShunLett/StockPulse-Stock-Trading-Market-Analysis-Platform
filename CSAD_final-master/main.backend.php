<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "includes/functions.inc.php";
require_once "includes/dbh.inc.php";
require_once "APIFetch/callAPI.php";
require_once "APIFetch/callTrendingAPI.php";


if (!isset($_SESSION['userId'])) {
    header("Location: error.php"); // Redirect to error page
    exit();
}
$userId = $_SESSION["userId"];


$total_cash = getUserAmountById($conn, $userId);

$net_total = $total_cash;

$total_market = 0;

$net_change = 0;

$net_percent_change = 0;

$possessions = [];

$user_stocks = getPortfolioByUserId($conn, $userId);

//Trending stocks
$trendingStocksJson = callTrendingAPI();
$trendingStocks = getMostTrendingStockNames($trendingStocksJson);

 $trendingStocksToShow = [];
    for($i = 0; $i < count($trendingStocks); $i++) {
        if (doesStockExist($conn, $trendingStocks[$i])) {
            $jsonData = callAPI($trendingStocks[$i]);
            $priceData = extractPriceData($jsonData);
            $priceChangePercent = $priceData["priceChangePercent"];
            
        
            $temp = ["company_name"=>$trendingStocks[$i],
                    "Change"=>formatPriceChangePercent($priceChangePercent)];
            array_push($trendingStocksToShow, $temp);
        }
    }


//Loop through each stock in the user's portfolio, fetch API and then do necessary calculation
for($i = 0; $i < count($user_stocks); $i++) {


    $jsonData = callAPI($user_stocks[$i]["stockShortName"]);
    $priceData = extractPriceData($jsonData);


    $total = $user_stocks[$i]["netQuantity"] * $priceData['currentPrice']; //quantity * current stock price

    $total_market += $total; 
    $net_total += $total; //add stock total value to net total


    //get the total purchased price of a stock 
    //same stock may be bought at different time with different price and quantity
    //therefore loop through the database history related to userId and stockId
    $purchased_price = getTotalPurchasePriceForStock($conn, $userId, getStockIdByShortName($conn, $user_stocks[$i]["stockShortName"]));

    
    $change = $total - $purchased_price;
    if ($total != 0) {
        $percent = ($change / $total) * 100;
    } else {
        $percent = 0; // Or any other default value you prefer
    }

    $net_change += $change; //change amount to total stock value;

    $temp = ["company_name" => $user_stocks[$i]["stockShortName"], 
    "company_full_name" => $user_stocks[$i]["stockLongName"], 
    "Total" => round($total, 2), 
    "Quantity" => $user_stocks[$i]["netQuantity"],
    "Change" => $percent >= 0 ? "+".round($change, 2) : round($change, 2),
    "Per_Stock" =>  round($priceData['currentPrice'], 2)];

    array_push($possessions, $temp);
}
if ($net_total != 0) {
    $net_percent_change = ($net_change/$net_total) * 100;
} else {
    $net_percent_change = 0;
}

$net_change = number_format($net_change, 2);
$net_percent_change = number_format($net_percent_change, 2);


if ($net_change >= 0) {
    $string_change = "<p style='color:#4caf50'>Live Total Profit +$net_change "; 
    $string_change .= " , +$net_percent_change% </p>";
} else {
    $string_change = "<p style='color:red'>Live Total Profit $net_change"; 
    $string_change .= " , $net_percent_change%</p>";
}




