<?php
 session_start(); // Start the session
 if (!isset($_SESSION['userId'])) {
     header("Location: error.php"); // Redirect to error page
     exit();
 }
require_once "main.backend.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Stock Dashboard</title>
<link rel="stylesheet" href="css/main.css">
<script src="js/main.js" defer></script>
</head>
<body>
<main>
    <?php include 'header.php'; ?>
    <div class="main-container">
        <div class="left-container">
            <div class="account-summary">
                <h2>
                    <span id="toggle-visibility"><img src="images/eye-logo.svg" alt="Toggle Visibility"></span>
                    <pre>Net Account: </pre>
            
                </h2>
                <div class="account-details">
    
                    <p id="account-amount">USD <?php echo number_format($net_total, 2); ?></p>

                    <?php echo $string_change; ?>

                    <div class="totals">
                         <!-- set amount and reset button -->
                        <div class="input-container" >
                                <!-- set amount button -->
                                <form action="set_amount_backend.php" method="POST" id="setAmountForm">
                                    <input type="number" id="account-input" placeholder="Enter amount" name="amount">
                                    <button type="submit" id="submit-button" class="button">Set Amount</button>
                                </form>

                                <!-- reset button -->
                                <form action="reset_backend.php" method="POST" id="reset">
                                    <button type="submit"id="reset-button" class="button reset">Reset</button>
                                </form>
                        </div>
                
                        <!-- -->
                        <p id="total-market-value">Total Market Value: $ <?php echo number_format($total_market, 2); ?></p>
                        <p id="total-cash">Cash Left: $ <?php echo number_format($total_cash, 2); ?></p>
                        <p id="error-message">Amount Cannot Be Empty</p>
                    </div>
                </div>
            </div>
            <div class="possession-container">
                <h2>In Possession</h2>
                <div class="possession-list">
                    <?php 
                        // include 'generate_possession.php'; 
                        generatePossessionItems($possessions);
                    ?>
                </div>
            </div>
        </div>
        <div class="stocks-container">
            <h2>Most Trending Stocks</h2>
            <div class="stock-list">
                <?php 
                 generateStockItems($trendingStocksToShow) ?>
            </div>
        </div>
    </div>
</main>
<script>
       document.getElementById('setAmountForm').addEventListener('submit', function(event) {
            var amountInput = document.getElementById('account-input').value;
            var errorMessage = document.getElementById('error-message');
            if (amountInput === '') {
                errorMessage.style.display = 'block';
                event.preventDefault(); // Prevent form submission
            } else {
                errorMessage.style.display = 'none';
            }
        });

        document.getElementById('reset').addEventListener('submit', function(event) {
            if (!confirm("This will reset the amount to zero, and you will lose all current holdings and purchase history!")) {
                event.preventDefault();
            }
        });
    </script>
</body>

</html>
