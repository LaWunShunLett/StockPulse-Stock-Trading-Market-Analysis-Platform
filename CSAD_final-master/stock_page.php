<?php

 

session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: error.php"); // Redirect to error page
    exit();
}
$userId = $_SESSION["userId"];

$company = isset($_GET['company']) ? urldecode($_GET['company']) : 'Unknown';

require_once "stock_page_backend.php";

// Further processing with $company
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin-top: 60px;
        }
    </style>
    <title><?php echo htmlspecialchars($company); ?> Stock Information</title>
    <meta name="title" content="Stock Page">
    <meta name="description" content="<?php echo htmlspecialchars($company); ?> stock information">
    <!-- <link rel="shortcut icon" href="images/favicon.svg" type="image/svg+xml"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/stock_page.css">
</head>
<body>
<?php include 'header.php'; ?>
    <main>
        <article class="container" data-container>
            <div class="content-left">
                <section class="section current-stock" aria-label="current stock" data-current-stock>
                    <div class="card card-lg current-stock-card">
                        <h2 class="title-2 card-title"><span style="font-size: 2.2rem;"><?php echo htmlspecialchars($company); ?></span></h2>
                        <div class="weapper">
                            <p style="font-size: 4rem; font-weight: 400;"><?php echo $priceData["currentPrice"]?><sub>$</sub></p> <!--Price -->
                            <img src="images/<?php echo htmlspecialchars($company); ?>.png" alt="Overcast Clouds" class="circular-image">
                        </div>
                        <p class="body-3"><?php echo htmlspecialchars($stock_description) ?>.</p>
                        <ul class="meta-list">
                            <li class="meta-item">

                               <!-- add to watchlist button -->
                               <button class="icon-button" id="addToWatchlistButton" 
                                data-user-id="<?php echo htmlspecialchars($userId); ?>" 
                                data-stock-name="<?php echo htmlspecialchars($company); ?>" watchlist=<?php echo htmlspecialchars($stock_exists); ?>>
                                 <i class="fa-solid fa-heart" id="heart_btn"></i>
                                </button>

                            <p class="title-3 meta-text">Add to watchlist</p> 
                            </li>
                        </ul>
                        <div class="container"> <!-- Buy -->
                            <button style="margin-left: auto" class="buy-button" onclick="location.href='buy_sell.php?company=<?php echo $company?>'">Buy/Sell</button>
                        </div>
                    </div>
                </section>
                <!-- Recommendation Trends-->


                </div>
<!-- Start from here for the div class-->
                <div class="content-right">
                    <section class="section highlights" aria-labelledby="highlights-label" data-highlights>
                        <div class="card card-lg">
                            <div class="highlight-list">
                                <div class="card card-sm highlight-card one">
                                   
                                    <p class="middle">Day's Range</p>
                                    <br>
                                    <div class="currentValue">Current Price: $<?php echo htmlspecialchars($currentPrice); ?></div>
                                    
                                    <?php echo '<div style="display:flex; text-size=x-larger; flex-direction: row; justify-content:space-between;"><p>'.htmlspecialchars($dayLow).'</p>' . '<p>' . htmlspecialchars($dayHigh).'</p></div>' ; ?>
                                    <div class="slideContainer1">
                                        
                                      
                                        

                                        <input type="range" min="<?php echo htmlspecialchars($dayLow); ?>" max="<?php echo htmlspecialchars($dayHigh); ?>" value="<?php echo htmlspecialchars($currentPrice); ?>" class="slideRange" disabled>
                                    </div>  


                                </div>
                                <div class="card card-sm highlight-card two">
                                     <p class="middle">52 Week Range</p>
                                     <br>

                                     <div class="currentValue">Current Price: $<?php echo htmlspecialchars($fiftyTwoWeekCurrent); ?></div>
                                    
                                      <?php echo '<div style="display:flex; flex-direction: row; justify-content:space-between;"><p>'.htmlspecialchars($fiftyTwoWeekLow).'</p>' . '<p>' . htmlspecialchars($fiftyTwoWeekHigh).'</p></div>' ; ?>
                                    <div class="slideContainer1">
                                    
                                        <input type="range" min="<?php echo htmlspecialchars($fiftyTwoWeekLow); ?>" max="<?php echo htmlspecialchars($fiftyTwoWeekHigh); ?>" value="<?php echo htmlspecialchars($fiftyTwoWeekCurrent); ?>" class="slideRange" disabled>
                                    </div>
                                </div>
                           
                            <div class="card card-sm highlight-card"><h3 class="title-3">Market Capital</h3>
                                <div class="wrapper" id="market_cap">
                                    <i class="fa-solid fa-sack-dollar fa-2xl"></i>
                                    <p class="title-1"><?php echo htmlspecialchars($financialMetrics['marketCap'] ?? 'N/A')?></p>
                                </div>
                            </div>
                            <div class="card card-sm highlight-card"><h3 class="title-3">PE Ratio</h3>
                                <div class="wrapper" id="pe_ratio">
                                    <i class="fa-solid fa-divide fa-2xl"></i>
                                    <p class="title-1"><?php echo htmlspecialchars($financialMetrics['PE ratio'] ?? 'N/A')?></p>
                                </div>
                            </div>
                            <div class="card card-sm highlight-card"><h3 class="title-3">Beta</h3>
                                <div class="wrapper" id="beta">
                                    <i class="fa-solid fa-chart-line fa-xl"></i>
                                    <p class="title-1"><?php echo htmlspecialchars($financialMetrics['beta'] ?? 'N/A')?></p>
                                </div>
                            </div>
                            <div class="card card-sm highlight-card"><h3 class="title-3">Dividend Rate</h3>
                                <div class="wrapper" id="dividend_rate">
                                    <i class="fa-solid fa-money-check-dollar fa-2xl"></i>
                                    <p class="title-1"><?php echo htmlspecialchars($financialMetrics['dividendRate'] ?? 'N/A')?></p>
                                </div>
                            </div>
                        </div>
                </div>
                </section>
                <section class="section forecast" aria-labelledby="forecast-label" data-5-day-forecast>
    <h2 class="title-2" id="forecast-label">
        <p style="font-size: 2rem;">RECOMMENDATION TREND</p>
        <div class="card card-lg forecast-card">
            <div class="skill">
                <li>
                    <p>Strong Buy</p>
                    <span class="bar"><span class="StrongBuy" style="width: <?php echo htmlspecialchars($recommendationTrends['strongBuy']); ?>%;"></span></span>
                </li>
                <li>
                    <p>Buy</p>
                    <span class="bar"><span class="buy" style="width: <?php echo htmlspecialchars($recommendationTrends['buy']); ?>%;"></span></span>
                </li>
                <li>
                    <p>Hold</p>
                    <span class="bar"><span class="hold" style="width: <?php echo htmlspecialchars($recommendationTrends['hold']); ?>%;"></span></span>
                </li>
                <li>
                    <p>Sell</p>
                    <span class="bar"><span class="sell" style="width: <?php echo htmlspecialchars($recommendationTrends['sell']); ?>%;"></span></span>
                </li>
                <li>
                    <p>Strong sell</p>
                    <span class="bar"><span class="strongSell" style="width: <?php echo htmlentities($recommendationTrends['strongSell']); ?>%;"></span></span>
                </li>
            </div>
        </div>
    </h2>
</section>
                <!-- End before Hourly section-->
        </article>
    </main>
  
</body>

<script>
      document.addEventListener('DOMContentLoaded', (event) => {
            //add to watchlist
            document.getElementById('addToWatchlistButton').addEventListener('click', function() {
                console.log("clicked");
                document.getElementById('heart_btn').style.color = "red";
                var user_id = this.getAttribute('data-user-id');
                var stock_name = this.getAttribute('data-stock-name');

                var xhr = new XMLHttpRequest();
                xhr.open("POST", "add_to_watchlist_backend.php", true); //directory to send
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        var status = xhr.status;
                        if (status === 0 || (status >= 200 && status < 400)) {
                            // The request has been completed successfully
                            console.log("Request is Successful")
                        } else {
                            // Handle error case
                            console.log("Error: " + xhr.statusText)
                        }
                    }
                };
                var data = "userId=" + encodeURIComponent(user_id) + "&stockName=" + encodeURIComponent(stock_name);
                xhr.send(data);
            });

            //if stock exists in watchlist change the color fo button
            var btn = document.getElementById('addToWatchlistButton');
            var heart_btn = document.getElementById('heart_btn');
            if (btn.getAttribute('watchlist') == true) {
                heart_btn.style.color = "red";
            } else {
               heart_btn.style.color = "none";
            }
     })
</script>


</html>