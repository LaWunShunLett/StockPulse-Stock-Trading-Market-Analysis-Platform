<?php
     session_start(); // Start the session
     if (!isset($_SESSION['userId'])) {
         header("Location: error.php"); // Redirect to error page
         exit();
     }
    require_once "watchlist_backend.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Watchlist</title>
    <link rel="stylesheet" href="css/watchlist.css">
    <script src="js/watchlist.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="table" id="watchlist_table">
        <div class="table__header">
            <div class="header-left">
                <img src="images/watchlist-logo.svg" alt="Menu" class="menu-icon">
                <h1>My Watchlist</h1>
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
                // include 'watchlist_table.php'; 
                generateWatchlistTable($watchlist_item_table);
                ?>
            </table>
        </div>
    </main>
    <script>

function confirmDelete(stockName) {
    if (confirm("Want to delete " + stockName + " from watchlist?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_from_watchlist_backend.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert(stockName + " has been deleted from your watchlist.");
                        // Optionally, you can refresh the page or remove the row from the table
                        location.reload();
                    } else {
                        alert("Error: " + response.message);
                    }
                } catch (e) {
                    console.log("Failed to parse JSON response:", xhr.responseText);
                    alert("An unexpected error occurred.");
                }
            }
        };
        xhr.send("stockShortName=" + encodeURIComponent(stockName));
    } else {
        console.log("Deletion cancelled");
    }
}

    document.addEventListener('DOMContentLoaded', function () { //on right click
    const tableRows = document.querySelectorAll('tr');
    tableRows.forEach(row => {
        row.addEventListener('contextmenu', function (e) {
            e.preventDefault();
            const stockName = this.getAttribute('data-stock-name');
            confirmDelete(stockName);
        });
    });
});
</script>
    </script>
</body>
</html>
