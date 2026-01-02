<?php
     session_start(); // Start the session
     if (!isset($_SESSION['userId'])) {
         header("Location: error.php"); // Redirect to error page
         exit();
     }
    require_once "history_backend.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock History</title>
    <link rel="stylesheet" href="css/watchlist.css">
    <script src="js/watchlist.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="table" id="watchlist_table">
        <div class="table__header">
            <div class="header-left">
                <img src="images/watchlist-logo.svg" alt="Menu" class="menu-icon">
                <h1>History</h1>
            </div>
            <div class="header-right">
                <div class="dropdown">
                    <button id="exportBtn">Export</button>
                    <div id="dropdownContent" class="dropdown-content">
                        <button id="toPDF">Export to PDF</button>
                        <button id="toJSON">Export to JSON</button>
                        <!--
                        <button id="toCSV">Export to CSV</button>
                        <button id="toEXCEL">Export to Excel</button>
                        -->
                    </div>
                </div>
            </div>
        </div>
        <div style="overflow-x: scroll" class="table__body">
            <table>
                <?php 
                // include 'history_backend.php'; 
                generateHistoryTable($history_table);
                ?>
            </table>
        </div>
    </main>
</body>
</html>
