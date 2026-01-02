<?php

 if (!isset($_SESSION['userId'])) {
     header("Location: error.php"); // Redirect to error page
     exit();
 }
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockPulse</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="header.css">
</head>
<body>
    <div class="nav-container">
        <nav>
            <ul class="mobile-nav">
                <li>
                    <div class="menu-icon-container">
                        <div class="menu-icon">
                            <span class="line-1"></span>
                            <span class="line-2"></span>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="#" class="link-logo"></a>
                </li>
                <li>
                    <a href="#" class="link-search"></a>
                </li>
            </ul>
            <ul class="desktop-nav">
                <li>
                    <a href="#" class="link-logo"></a>
                </li>
                <li>
                    <a href="main.php">Home</a>
                </li>
                <li>
                    <a href="watchlist.php">Watchlist</a>
                </li>
                <li>
                    <a href="support.php">Support</a>
                </li>
                <li>
                    <a href="profile.php">Profile</a>
                </li>
                <li>
                    <a href="history.php">Purchase History</a>
                </li>
                <li>
                    <a href="#" class="link-search"></a>
                </li>
            </ul>
        </nav>
        <div class="search-container hide">
            <div class="link-search"></div>
            <div class="search-bar">
                <form action="">
                    <input type="text" id="searchInput" placeholder="Search">
                </form>
                <div class="result-box hide" id="resultBox"></div>
            </div>
            <div class="link-close"></div>
            <div class="quick-links-container">
                <div class="quick-links">
                    <h2>Quick Links</h2>
                    <ul>
                        <li><a href="quickLink.php?categoryId=1">Technology</a></li>
                        <li><a href="quickLink.php?categoryId=2">Healthcare and Pharmaceutical</a></li>
                        <li><a href="quickLink.php?categoryId=3">Finance and Real Estate</a></li>
                        <li><a href="quickLink.php?categoryId=4">Consumer Goods and Service</a></li>
                        <li><a href="quickLink.php?categoryId=5">Energy and Industrials</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mobile-search-container hide" id="mobileSearchContainer">
            <div class="search-bar">
                <form action="">
                    <input type="text" id="mobileSearchInput" placeholder="Search">
                </form>
            </div>
            <span class="cancel-btn">Cancel</span>
            <div class="result-box hide" id="mobileResultBox"></div>
            <div class="quick-links" id="mobileQuickLinks">
                <h2>Quick Links</h2>
                <ul>
                <li><a href="quickLink.php?categoryId=1">Technology</a></li>
                        <li><a href="quickLink.php?categoryId=2">Healthcare and Pharmaceutical</a></li>
                        <li><a href="quickLink.php?categoryId=3">Finance and Real Estate</a></li>
                        <li><a href="quickLink.php?categoryId=4">Consumer Goods and Service</a></li>
                        <li><a href="quickLink.php?categoryId=5">Energy and Industrials</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="overlay"></div>
    <script src="js/header.js"></script>
</body>
</html>