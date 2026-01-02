<?php
require_once "includes/functions.inc.php";
require_once "includes/dbh.inc.php";

session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: error.php"); // Redirect to error page
    exit();
}

// Check if the 'category' parameter exists in the URL
if (isset($_GET['categoryId'])) {
    // Retrieve the 'category' parameter value
    $categoryId = $_GET['categoryId'];
    
    $categoryId = htmlspecialchars($categoryId, ENT_QUOTES, 'UTF-8'); // Sanitize the 'category' parameter value to prevent XSS attacks

    $stocksByCategory = getStocksByCategory($conn, $categoryId);

    $categoryName = getCategoryNameById($conn, $categoryId);
    
} else {
    //header("location: error.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickLink</title>
    <link rel="stylesheet" href="css/watchlist.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="table" id="watchlist_table">
        <div class="table__header">
            <div class="header-left">
                <h1><?php echo $categoryName ?></h1>
            </div>
        </div>
        <div style="overflow-x: scroll" class="table__body">
            <table>
                
               <?php generateStockTableForCategory($stocksByCategory); ?>
            </table>
        </div>
    </main>
</body>
</html>
