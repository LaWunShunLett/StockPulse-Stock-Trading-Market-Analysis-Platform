<?php

/* START of functions related to Sign In, Sign Up */
function uidExists($conn, $username, $email) {
    $sql = "SELECT  * FROM users WHERE userName = ? OR userEmail = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../logInTesting.php?error=stmfailed"); //database failed
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        //if the user name and email already exist in database
        return $row;
        
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function createUser($conn, $username, $email, $password) {
    $sql = "INSERT INTO users (userName, userEmail, userPwd) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../logInTesting.php?error=stmfailed"); //database failed
         exit();
    }   

    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);


    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPwd);
    mysqli_stmt_execute($stmt);

    //Do something (send the user to Home page?)

    mysqli_stmt_close($stmt);
}

function loginUser($conn, $username, $password) {
    $uidExists = uidExists($conn, $username, $password);

    if($uidExists === False) {
        header("location: ../signInSignUp.php?error=userDoesNotExist&username=$username");
        exit();
    }  

    $pwdHashed = $uidExists["userPwd"];
    $checkPwd = password_verify($password, $pwdHashed);

    if ($checkPwd === false) {
        header("location: ../signInSignUp.php?error=wrongPassword&username=$username");
        exit();
    } else if ($checkPwd === true) {
        session_start(); //important
        $_SESSION["userId"] = $uidExists["userId"];
        $_SESSION["userName"] = $uidExists["userName"];
        $_SESSION["userEmail"] = $uidExists["userEmail"];
        header("location: ../main.php"); //login successful
        exit();
    }
}
/* END of functions related to Sign In, Sign Up */


/* START of conversion functions */
function getStockShortNameById($conn, $stock_id) {
    $stockShortName = null;
    $sql = "SELECT stockShortName FROM stocks WHERE stock_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return "Prepare failed: " . $conn->error;
    }
    $stmt->bind_param("i", $stock_id);
    $stmt->execute();
    $stmt->bind_result($stockShortName);
    $stmt->fetch();
    $stmt->close();

    return $stockShortName;
}

function getStockIdByShortName($conn, $stockShortName) {
    $stockId = null;
    $sql = "SELECT stock_id FROM stocks WHERE stockShortName = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return "Prepare failed: " . $conn->error;
    }
    $stmt->bind_param("s", $stockShortName);
    $stmt->execute();
    $stmt->bind_result($stockId);
    $stmt->fetch();
    $stmt->close();

    return $stockId;
}

function getCategoryNameById($conn, $categoryId) {
    $sql = "SELECT categoryName FROM Category WHERE category_id = ?";
    $categoryName = "";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $stmt->bind_result($categoryName);
    $stmt->fetch();
    $stmt->close();

    return $categoryName;
}
/* END of conversion functions */


/* START of functions related to Users */

function setUsername($conn, $userId, $username) {
    $sql = "UPDATE users SET userName = ? WHERE userId = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return "Prepare failed: " . $conn->error;
    }
    $stmt->bind_param("si", $username, $userId);
    if ($stmt->execute()) {
        $stmt->close();
        return "Username updated successfully.";
    } else {
        $stmt->close();
        return "Error updating username: " . $stmt->error;
    }
}

function setUserEmail($conn, $userId, $userEmail) {
    $sql = "UPDATE users SET userEmail = ? WHERE userId = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return "Prepare failed: " . $conn->error;
    }
    $stmt->bind_param("si", $userEmail, $userId);
    if ($stmt->execute()) {
        $stmt->close();
        return "User email updated successfully.";
    } else {
        $stmt->close();
        return "Error updating user email: " . $stmt->error;
    }
}

function updateUserAmount($conn, $userId, $newAmount) {
    // Prepare the SQL statement
    $sql = "UPDATE users SET amount = ? WHERE userId = ?";
    
    // Initialize the statement
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die('Error preparing the statement: ' . $conn->error);
    }
    
    // Bind parameters to the statement
    $stmt->bind_param("ii", $newAmount, $userId);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Check if any row was updated
        if ($stmt->affected_rows > 0) {
            return "User amount updated successfully.";
        } else {
            return "No changes made or user not found.";
        }
    } else {
        return "Error updating user amount: " . $stmt->error;
    }
    
    // Close the statement
    $stmt->close();
}

function resetUserAccount($conn, $userId) {
    // Start a transaction
    $conn->begin_transaction();
    
    try {
        // Set the user's amount to zero
        $sql = "UPDATE users SET amount = 0 WHERE userId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        if (!$stmt->execute()) {
            throw new Exception("Error updating user amount: " . $stmt->error);
        }
        $stmt->close();

        // Delete all the user's stocks from the portfolio
        $sql = "DELETE FROM portfolio WHERE userId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        if (!$stmt->execute()) {
            throw new Exception("Error deleting user portfolio: " . $stmt->error);
        }
        $stmt->close();

        // Delete all the user's orders
        $sql = "DELETE FROM orders WHERE userId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        if (!$stmt->execute()) {
            throw new Exception("Error deleting user orders: " . $stmt->error);
        }
        $stmt->close();

        // Commit the transaction
        $conn->commit();
        
        return "User account reset successfully.";
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        return "Error resetting user account: " . $e->getMessage();
    }
}
/* END of functions related to Users */


/* START of functions related to Watchlist */
function addToWatchlist($conn, $user_id, $stock_id) {
    
    // Check if the watchlist already exists for the user
    $checkWatchlist = "SELECT watchlistId FROM watchlist WHERE userId = ?";
    $stmt = $conn->prepare($checkWatchlist);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        // If no watchlist exists, create one
        $createWatchlist = "INSERT INTO watchlist (userId) VALUES (?)";
        $stmt = $conn->prepare($createWatchlist);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $watchlist_id = $stmt->insert_id;
    } else {
        // If watchlist exists, get the watchlist_id
        $row = $result->fetch_assoc();
        $watchlist_id = $row['watchlistId'];
    }
    
    // Check if the stock is already in the watchlist
    $checkWatchlistItem = "SELECT * FROM watchlist_items WHERE watchlistId = ? AND stock_id = ?";
    $stmt = $conn->prepare($checkWatchlistItem);
    $stmt->bind_param("ii", $watchlist_id, $stock_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        // Insert the stock into the watchlist
        $insertWatchlistItem = "INSERT INTO watchlist_items (watchlistId, stock_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertWatchlistItem);
        $stmt->bind_param("ii", $watchlist_id, $stock_id);
        if ($stmt->execute()) {
            echo "Stock added to watchlist successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Stock is already in the watchlist.";
    }

    $stmt->close();
}

function getWatchlist($conn, $user_id) {

    $sql = "SELECT wi.stock_id, s.stockShortName, s.stockLongName, s.stockDescription
            FROM watchlist_items wi
            JOIN watchlist w ON wi.watchlistId = w.watchlistId
            JOIN stocks s ON wi.stock_id = s.stock_id
            WHERE w.userId = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $watchlist = [];
    while ($row = $result->fetch_assoc()) {
        $watchlist[] = $row;
    }

    $stmt->close();
    return $watchlist;
}

function deleteFromWatchlist($conn, $user_id, $stock_id) {
    
    
    // Check if the watchlist exists for the user
    $checkWatchlist = "SELECT watchlistId FROM watchlist WHERE userId = ?";
    $stmt = $conn->prepare($checkWatchlist);
    if (!$stmt) {
        return "Prepare failed: " . $conn->error;
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        return "Watchlist does not exist for this user.";
    } else {
        // If watchlist exists, get the watchlist_id
        $row = $result->fetch_assoc();
        $watchlist_id = $row['watchlistId'];
        
        // Check if the stock is in the watchlist
        $checkWatchlistItem = "SELECT * FROM watchlist_items WHERE watchlistId = ? AND stock_id = ?";
        $stmt = $conn->prepare($checkWatchlistItem);
        if (!$stmt) {
            return "Prepare failed: " . $conn->error;
        }
        $stmt->bind_param("ii", $watchlist_id, $stock_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            return "Stock is not in the watchlist.";
        } else {
            // Delete the stock from the watchlist
            $deleteWatchlistItem = "DELETE FROM watchlist_items WHERE watchlistId = ? AND stock_id = ?";
            $stmt = $conn->prepare($deleteWatchlistItem);
            if (!$stmt) {
                return "Prepare failed: " . $conn->error;
            }
            $stmt->bind_param("ii", $watchlist_id, $stock_id);
            if ($stmt->execute()) {
                return "Stock removed from watchlist successfully.";
            } else {
                return "Error: " . $stmt->error;
            }
        }
    }

    $stmt->close();
}

function generateWatchlistTable($keyValueArray) {
    echo '<thead><tr>';
    if (count($keyValueArray) > 0) {
        $headers = array_keys($keyValueArray[0]);
        foreach ($headers as $header) {
            if ($header !== 'company_full_name') {
                echo '<th>' . htmlspecialchars($header) . '</th>';
            }
        }
    }
    echo '</tr></thead>';
    echo '<tbody>';
    foreach ($keyValueArray as $stock) {
        $href = 'stock_page.php?company=' . urlencode($stock['Company']); // Change this to the desired URL
        echo '<tr data-stock-name="' . htmlspecialchars($stock['Company']) . '" onclick="window.location.href=\'' . $href . '\'">';
        foreach ($headers as $header) {
            if ($header !== 'company_full_name') {
                echo '<td>';
                if ($header === 'Logo') {
                    echo '<div class="company-details-wrapper">' . $stock[$header] . '</div>';
                } else if ($header === 'Company') {
                    echo '<div class="company-details">';
                    echo '<div>' . htmlspecialchars($stock[$header]) . '</div>';
                    if (isset($stock['company_full_name'])) {
                        echo '<div>' . htmlspecialchars($stock['company_full_name']) . '</div>';
                    }
                    echo '</div>';
                } else if ($header === 'Price') {
                    $priceClass = $stock['Change'][0] === '+' ? 'positive_back' : 'negative_back';
                    echo '<div class="price-wrapper"><div class="price ' . $priceClass . '">' . htmlspecialchars($stock[$header]) . '</div></div>';
                } else if ($header === 'Change') {
                    $changeClass = $stock['Change'][0] === '+' ? 'positive' : 'negative';
                    echo '<div class="price-wrapper"><div class="price ' . $changeClass . '">' . htmlspecialchars($stock[$header]) . '</div></div>';
                } else {
                    echo htmlspecialchars($stock[$header]);
                }
                echo '</td>';
            }
        }
        echo '</tr>';
    }
    echo '</tbody>';
}

function isStockInWatchlist($conn, $userId, $stockId) {
    $sql = "SELECT COUNT(*) FROM watchlist_items 
            JOIN watchlist ON watchlist.watchlistId = watchlist_items.watchListId
            WHERE watchlist.userId = ? AND watchlist_items.stock_id = ?";
    $count = 0;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $stockId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count > 0;
}

function deleteStockFromWatchlist($conn, $userId, $stockId) {
    // Prepare the SQL statement to delete the stock from the watchlist
    $sql = "DELETE wi FROM watchlist_items wi 
            JOIN watchlist w ON wi.watchListId = w.watchlistId 
            WHERE w.userId = ? AND wi.stock_id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters
        $stmt->bind_param("ii", $userId, $stockId);

        // Execute the statement
        if ($stmt->execute()) {
            $stmt->close();
            return "Stock deleted successfully from watchlist.";
        } else {
            $stmt->close();
            return "Error deleting stock from watchlist: " . $stmt->error;
        }
    } else {
        return "Error preparing the statement: " . $conn->error;
    }
}
/* END of the watchlist functions related to Watchlist */


/* START of functions related to Buy and Sell */
function buyStock($conn, $userId, $stockId, $quantity, $orderActionId, $pricePerStock) {
    // Start a transaction
    $conn->begin_transaction();
    $userAmount = null;
    try {
        // Calculate the total price of the stock purchase
        $totalPrice = $quantity * $pricePerStock;

        // Check if the user has enough funds
        $sql = "SELECT amount FROM users WHERE userId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($userAmount);
        $stmt->fetch();
        $stmt->close();

        if ($userAmount < $totalPrice) {
            throw new Exception("Insufficient funds");
        }

        // Insert the new order into the orders table
        $sql = "INSERT INTO orders (userId, stock_id, orderTime, quantity, orderActionId, price) VALUES (?, ?, NOW(), ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiid", $userId, $stockId, $quantity, $orderActionId, $pricePerStock);
        if (!$stmt->execute()) {
            throw new Exception("Error inserting new order: " . $stmt->error);
        }
        $stmt->close();

        // Update the portfolio table
        $sql = "INSERT INTO portfolio (userId, stock_id, netQuantity) VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE netQuantity = netQuantity + VALUES(netQuantity)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $userId, $stockId, $quantity);
        if (!$stmt->execute()) {
            throw new Exception("Error updating portfolio: " . $stmt->error);
        }
        $stmt->close();

        // Deduct the total price from the user's amount
        $sql = "UPDATE users SET amount = amount - ? WHERE userId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $totalPrice, $userId);
        if (!$stmt->execute()) {
            throw new Exception("Error updating user amount: " . $stmt->error);
        }
        $stmt->close();

        // Commit the transaction
        $conn->commit();

        return [
            'status' => 'success',
            'message' => 'Stock bought successfully.'
        ];
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

function sellStock($conn, $userId, $stockId, $quantity, $orderActionId, $pricePerStock) {
    // Start a transaction
    $conn->begin_transaction();
    $netQuantity = null;
    try {
        // Calculate the total price of the stock sale
        $totalPrice = $quantity * $pricePerStock;

        // Check if the user has enough stocks in the portfolio
        $sql = "SELECT netQuantity FROM portfolio WHERE userId = ? AND stock_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $stockId);
        $stmt->execute();
        $stmt->bind_result($netQuantity);
        $stmt->fetch();
        $stmt->close();

        if ($netQuantity < $quantity) {
            throw new Exception("Insufficient stocks in portfolio to sell");
        }

        // Insert the new order into the orders table
        $sql = "INSERT INTO orders (userId, stock_id, orderTime, quantity, orderActionId, price) VALUES (?, ?, NOW(), ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiid", $userId, $stockId, $quantity, $orderActionId, $pricePerStock);
        if (!$stmt->execute()) {
            throw new Exception("Error inserting new order: " . $stmt->error);
        }
        $stmt->close();

        // Update the portfolio table
        $sql = "UPDATE portfolio SET netQuantity = netQuantity - ? WHERE userId = ? AND stock_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $quantity, $userId, $stockId);
        if (!$stmt->execute()) {
            throw new Exception("Error updating portfolio: " . $stmt->error);
        }
        $stmt->close();

        // If the netQuantity becomes zero, delete the row from the portfolio
        if ($netQuantity == $quantity) {
            $sql = "DELETE FROM portfolio WHERE userId = ? AND stock_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $stockId);
            if (!$stmt->execute()) {
                throw new Exception("Error deleting portfolio entry: " . $stmt->error);
            }
            $stmt->close();
        }

        // Add the total price to the user's amount
        $sql = "UPDATE users SET amount = amount + ? WHERE userId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $totalPrice, $userId);
        if (!$stmt->execute()) {
            throw new Exception("Error updating user amount: " . $stmt->error);
        }
        $stmt->close();

        // Commit the transaction
        $conn->commit();
        
        return [
            'status' => 'success',
            'message' => 'Stock sold successfully.'
        ];
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}
/* END of functions related to Buy and Sell */


/* START of API Function */
function extractFinancialMetrics($jsonData) {
    $data = json_decode($jsonData, true);

    $trailingPE = $data['summaryDetail']['trailingPE']['fmt'] ?? null;
    $marketCap = $data['summaryDetail']['marketCap']['fmt'] ?? null;
    $beta = $data['summaryDetail']['beta']['fmt'] ?? null;
    $forwardDividendYield = $data['summaryDetail']['dividendYield']['fmt'] ?? null;
    $dividendRate = $data['summaryDetail']['dividendRate']['fmt'] ?? null;

    return [
        'PE ratio' => $trailingPE,
        'marketCap' => $marketCap,
        'beta' => $beta,
        'forwardDividendYield' => $forwardDividendYield,
        'dividendRate' => $dividendRate
    ];
}

function extractPriceData($jsonData) {
    // Decode the JSON data
    $data = json_decode($jsonData, true);

    // Define a helper function to safely extract data 
    //if there is no data, it will be replaced by null
    
   

    // Extract the required information
    $currentPrice = safeExtract($data, 'price.regularMarketPrice');
    $marketOpenPrice = safeExtract($data, 'price.regularMarketOpen');
    $dayHigh = safeExtract($data, 'price.regularMarketDayHigh');
    $dayLow = safeExtract($data, 'price.regularMarketDayLow');
    $previousClosePrice = safeExtract($data, 'price.regularMarketPreviousClose');
    $priceChange = safeExtract($data, 'price.regularMarketChange');
    $priceChangePercent = safeExtract($data, 'price.regularMarketChangePercent');
    $preMarketPrice = safeExtract($data, 'price.preMarketPrice');
    $preMarketChange = safeExtract($data, 'price.preMarketChange');
    $postMarketPrice = safeExtract($data, 'price.postMarketPrice');
    $postMarketChange = safeExtract($data, 'price.postMarketChange');
    $fiftyTwoWeekHigh = safeExtract($data, 'summaryDetail.fiftyTwoWeekHigh');
    $fiftyTwoWeekLow = safeExtract($data, 'summaryDetail.fiftyTwoWeekLow');

    // Return the extracted information as an associative array
    return [
        'currentPrice' => $currentPrice,
        'marketOpenPrice' => $marketOpenPrice,
        'dayHigh' => $dayHigh,
        'dayLow' => $dayLow,
        'previousClosePrice' => $previousClosePrice,
        'priceChange' => $priceChange,
        'priceChangePercent' => $priceChangePercent,
        'preMarketPrice' => $preMarketPrice,
        'preMarketChange' => $preMarketChange,
        'postMarketPrice' => $postMarketPrice,
        'postMarketChange' => $postMarketChange,
        'fiftyTwoWeekHigh' => $fiftyTwoWeekHigh,
        'fiftyTwoWeekLow' => $fiftyTwoWeekLow,
    ];
}

function extractRecommendationData($jsonData) {
    // Decode the JSON data
    $data = json_decode($jsonData, true);
   
    // Extract the required information
    $strongBuy = $data['recommendationTrend']['trend'][0]['strongBuy'] ?? 0;
    $buy = $data['recommendationTrend']['trend'][0]['buy'] ?? 0;
    $hold = $data['recommendationTrend']['trend'][0]['hold'] ?? 0;
    $sell = $data['recommendationTrend']['trend'][0]['sell'] ?? 0;
    $strongSell = $data['recommendationTrend']['trend'][0]['strongSell'] ?? 0;

    $total_recommendations = $strongBuy + $buy + $hold + $sell + $strongSell;

    // Check for division by zero
    if ($total_recommendations > 0) {
        // Return the extracted information as an associative array
        return [
            'strongBuy' => round(($strongBuy / $total_recommendations) * 100),
            'buy' => round(($buy / $total_recommendations) * 100),
            'hold' => round(($hold / $total_recommendations) * 100),
            'sell' => round(($sell / $total_recommendations) * 100),
            'strongSell' => round(($strongSell / $total_recommendations) * 100),
        ];
    } else {
        // If total_recommendations is zero, return zero for all percentages
        return [
            'strongBuy' => 0,
            'buy' => 0,
            'hold' => 0,
            'sell' => 0,
            'strongSell' => 0,
        ];
    }
}

function safeExtract($data, $keyPath) {
    $keys = explode('.', $keyPath);
    foreach ($keys as $key) {
        if (isset($data[$key])) {
            $data = $data[$key];
        } else {
            return null;
        }
    }
    return isset($data['fmt']) ? $data['fmt'] : 0;
}

//to use with trending stocks json data
function getMostTrendingStockNames($jsonData) {
    // Decode the JSON data into an associative array
    $data = json_decode($jsonData, true);

    // Initialize an empty array to hold the stock names
    $stockNames = [];

    // Check if the 'result' key exists in the 'finance' data
    if (isset($data['finance']['result'])) {
        // Loop through each screener result
        foreach ($data['finance']['result'] as $screener) {
            // Check if the screener is "Most Actives"
            if (isset($screener['title']) && $screener['title'] === 'Most Actives') {
                // Loop through the quotes and extract the stock names
                if (isset($screener['quotes'])) {
                    foreach ($screener['quotes'] as $quote) {
                        if (isset($quote['symbol'])) {
                            $stockNames[] = $quote['symbol'];
                        }
                    }
                }
                break; // Exit the loop once "Most Actives" is found
            }
        }
    }

    return $stockNames;
}
/* END of API function */  

/* START of HomePage Function */
function getPortfolioByUserId($conn, $userId) {
    $sql = "SELECT s.stockShortName, s.stockLongName, p.netQuantity
            FROM portfolio p
            JOIN stocks s ON p.stock_id = s.stock_id
            WHERE p.userId = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return "Prepare failed: " . $conn->error;
    }
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $stocks = [];
    while ($row = $result->fetch_assoc()) {
        $stocks[] = $row;
    }

    $stmt->close();
    return $stocks;
}

function setAmount($conn, $userId, $amount) {
    $sql = "UPDATE users SET amount = ? WHERE userId = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return "Prepare failed: " . $conn->error;
    }
    $stmt->bind_param("ii", $amount, $userId);
    if ($stmt->execute()) {
        $stmt->close();
        return "Amount updated successfully.";
    } else {
        $stmt->close();
        return "Error updating amount: " . $stmt->error;
    }
}

function getUserAmountById($conn, $userId) {
    // Prepare the SQL statement to prevent SQL injection
    $sql = "SELECT amount FROM users WHERE userId = ?";
    $stmt = $conn->prepare($sql);
    
    // Check if the preparation was successful
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    
    // Bind the userId parameter to the SQL statement
    $stmt->bind_param("i", $userId);
    
    // Execute the statement
    $stmt->execute();
    
    // Initialize the variable to avoid undefined variable error
    $amount = null;
    
    // Bind the result to the variable
    $stmt->bind_result($amount);
    
    // Fetch the result
    $stmt->fetch();
    
    // Close the statement
    $stmt->close();
    
    // Check if a value was fetched
    if ($amount === null) {
        die("No amount found for userId: " . htmlspecialchars($userId));
    }
    
    // Return the amount
    return $amount;
}

function generatePossessionItems($possessions) {
    echo '<table>';
    echo '<colgroup>';
    echo '<col style="width: 20%;">';
    echo '<col style="width: 20%;">';
    echo '<col style="width: 20%;">';
    echo '<col style="width: 20%;">';
    echo '<col style="width: 20%;">';
    echo '</colgroup>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Company</th>';
    echo '<th>Total</th>';
    echo '<th>Quantity</th>';
    echo '<th>Live P&L</th>';
    echo '<th>Per Stock</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($possessions as $possession) {
        $change = trim($possession['Change']);
       
        $changeClass = $change[0] == '-' ? 'change-negative' : 'change-positive';

        $href = 'stock_page.php?company=' . urlencode($possession['company_name']); // Change this to the desired URL
        
        echo '<tr class="possession-item" onclick="window.location.href=\'' . $href . '\'">';
        echo '<td>';
        echo '<div class="company-details">';
        echo '<div class="company">' . htmlspecialchars($possession['company_name']) . '</div>';
        echo '<div>' . htmlspecialchars($possession['company_full_name']) . '</div>';
        echo '</div>';
        echo '</td>';
        echo '<td>' . htmlspecialchars($possession['Total']) . '</td>';
        echo '<td>' . htmlspecialchars($possession['Quantity']) . '</td>';
        if ($change[0] === '-') {
            echo '<td style="color:#ff6b6b">' . htmlspecialchars($change) . '</td>';
        } else {
            echo '<td style="color:#4caf50">' . htmlspecialchars($change) . '</td>';
        }
      //  echo '<td class="' . htmlspecialchars($changeClass) . '">' . htmlspecialchars($change) . '</td>';
        echo '<td>' . htmlspecialchars($possession['Per_Stock']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}

function getTotalPurchasePriceForStock($conn, $userId, $stockId) {
    // Calculate total purchase price
    $sql = "SELECT SUM(price * quantity) AS totalPurchasePrice 
            FROM orders 
            WHERE userId = ? AND stock_id = ? AND orderActionId = (SELECT orderActionId FROM orderActions WHERE orderActionName = 'Buy')";
    $totalPurchasePrice = 0;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $stockId);
    $stmt->execute();
    $stmt->bind_result($totalPurchasePrice);
    $stmt->fetch();
    $stmt->close();

    // Calculate total sale price
    $sql = "SELECT SUM(price * quantity) AS totalSalePrice 
            FROM orders 
            WHERE userId = ? AND stock_id = ? AND orderActionId = (SELECT orderActionId FROM orderActions WHERE orderActionName = 'Sell')";
    $totalSalePrice = 0;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $stockId);
    $stmt->execute();
    $stmt->bind_result($totalSalePrice);
    $stmt->fetch();
    $stmt->close();

    // Calculate net purchase price
    $netPurchasePrice = $totalPurchasePrice - $totalSalePrice;

    return $netPurchasePrice;
}

function generateStockItems($stocks) {
    echo '<table>';
    echo '<tbody>';
    foreach ($stocks as $stock) {
        $changeClass = $stock['Change'][0] === '+' ? 'positive' : 'negative';
        $href = 'stock_page.php?company=' . urlencode($stock['company_name']); // Change this to the desired URL(details.php)
        
        echo '<tr class="stock-item" onclick="window.location.href=\'' . $href . '\'">';
        echo '<td class="company">' . htmlspecialchars($stock['company_name']) . '</td>';
        echo '<td class="change ' . $changeClass . '">' . htmlspecialchars($stock['Change']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}
/* END of HomePage Function */

/* START of History Function */
function getOrdersByUserId($conn, $userId) {
    // Prepare the SQL statement with ORDER BY clause for descending order based on orderTime
    $sql = "SELECT 
                o.orderId, 
                s.stockShortName, 
                s.stockLongName, 
                o.price, 
                o.quantity, 
                a.orderActionName, 
                o.orderTime AS orderPlaced 
            FROM 
                orders o
            JOIN 
                stocks s ON o.stock_id = s.stock_id
            JOIN 
                orderActions a ON o.orderActionId = a.orderActionId
            WHERE 
                o.userId = ?
            ORDER BY 
                o.orderTime DESC";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the userId parameter
        $stmt->bind_param("i", $userId);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Initialize an array to hold the orders
        $orders = [];

        // Fetch the result rows as an associative array
        while ($row = $result->fetch_assoc()) {
            $orders[] = [
                'orderId' => $row['orderId'],
                'stockShortName' => $row['stockShortName'],
                'stockLongName' => $row['stockLongName'],
                'price' => $row['price'],
                'quantity' => $row['quantity'],
                'orderActionName' => $row['orderActionName'],
                'orderPlaced' => $row['orderPlaced']
            ];
        }

        // Close the statement
        $stmt->close();

        // Return the orders array
        return $orders;
    } else {
        // Handle errors in preparing the statement
        throw new Exception("Error preparing the statement: " . $conn->error);
    }
}

function generateHistoryTable($keyValueArray) {
    echo '<thead><tr>';
    if (count($keyValueArray) > 0) {
        $headers = array_keys($keyValueArray[0]);
        foreach ($headers as $header) {
            if ($header !== 'company_full_name') {
                echo '<th>' . htmlspecialchars($header) . '</th>';
            }
        }
    }
    echo '</tr></thead>';
    echo '<tbody>';
    foreach ($keyValueArray as $stock) {
        foreach ($headers as $header) {
            if ($header !== 'company_full_name') {
                echo '<td>';
                if ($header === 'Logo') {
                    echo '<div class="company-details-wrapper">' . $stock[$header] . '</div>';
                } else if ($header === 'Company') {
                    echo '<div class="company-details">';
                    echo '<div>' . htmlspecialchars($stock[$header]) . '</div>';
                    if (isset($stock['company_full_name'])) {
                        echo '<div>' . htmlspecialchars($stock['company_full_name']) . '</div>';
                    }
                    echo '</div>';
                } else if ($header === 'Price') {
                    echo '<div class="price-wrapper"><div class="price">' . htmlspecialchars($stock[$header]) . '</div></div>';
                } else if ($header === 'Action') {
                    echo '<div class="price-wrapper"><div class="price">' . htmlspecialchars($stock[$header]) . '</div></div>';
                } else  {
                    echo htmlspecialchars($stock[$header]);
                }
                echo '</td>';
            }
        }
        echo '</tr>';
    }
    echo '</tbody>';
}
/* END of History Function */


/* START of Stocks Function */
function getStocksWithCategory($conn) {
    // SQL query to retrieve stock details along with category name
    $sql = "
        SELECT 
            stocks.stockShortName, 
            stocks.stockLongName, 
            category.categoryName 
        FROM 
            stocks 
        JOIN 
            category 
        ON 
            stocks.category_id = category.category_id
    ";
    
    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all rows and store in an array
    $stocks = array();
    while ($row = $result->fetch_assoc()) {
        $stocks[] = $row;
    }

    // Close the statement
    $stmt->close();

    return $stocks;
}

function getStocksByCategory($conn, $categoryId) {
    // Prepare the SQL statement
    $sql = "SELECT s.stockShortName, s.stockLongName, s.stockDescription 
            FROM stocks s
            JOIN Category c ON s.category_id = c.category_id
            WHERE s.category_id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the category_id parameter
        $stmt->bind_param("i", $categoryId);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Initialize an array to hold the stocks
        $stocks = [];

        // Fetch the result rows as an associative array
        while ($row = $result->fetch_assoc()) {
            $stocks[] = [
                'Logo'=>$row['stockShortName'],
                'Company' => $row['stockShortName'],
                'company_full_name' => $row['stockLongName'],
                'Description' => $row['stockDescription'],
                
            ];
        }

        // Close the statement
        $stmt->close();

        // Return the stocks array
        return $stocks;
    } else {
        // Handle errors in preparing the statement
        throw new Exception("Error preparing the statement: " . $conn->error);
    }
}

function generateStockTableForCategory($keyValueArray) {
    echo '<thead><tr>';
    if (count($keyValueArray) > 0) {
        $headers = array_keys($keyValueArray[0]);
        foreach ($headers as $header) {
            if ($header !== 'company_full_name') {
                echo '<th>' . htmlspecialchars($header) . '</th>';
            }
        }
    }
    echo '</tr></thead>';
    echo '<tbody>';
    foreach ($keyValueArray as $stock) {
        $href = 'stock_page.php?company=' . urlencode($stock['Company']); // Change this to the desired URL
        echo '<tr onclick="window.location.href=\'' . $href . '\'">';
    
            echo "<td>";
            echo '<img src="images/' . htmlspecialchars($stock['Company']) . '.png" alt="' . htmlspecialchars($stock['Company']) . '" class="company-logo">';
            echo "</td>";

            echo "<td>";
            echo '<div class="company-details">';
            echo '<div>' . htmlspecialchars($stock['Company']) . '</div>';
            echo '<div>' . htmlspecialchars($stock['company_full_name']) . '</div>';
            echo '</div>';
            echo "</td>";

            echo "<td>";
            echo htmlspecialchars($stock["Description"]);
            echo '</td>';

        echo '</tr>';
    }
    echo '</tbody>';
}

function doesStockExist($conn, $stockShortName) {
    // Prepare the SQL statement
    $sql = "SELECT COUNT(*) FROM stocks WHERE stockShortName = ?";
    
    // Initialize the return value
    $stockExists = false;
    $count = 0;
    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the stockShortName parameter
        $stmt->bind_param("s", $stockShortName);
        
        // Execute the statement
        $stmt->execute();
        
        // Bind the result variable
        $stmt->bind_result($count);
        
        // Fetch the result
        if ($stmt->fetch()) {
            // Check if the count is greater than 0
            $stockExists = ($count > 0);
        }
        
        // Close the statement
        $stmt->close();
    } else {
        // Handle errors in preparing the statement
        throw new Exception("Error preparing the statement: " . $conn->error);
    }
    
    return $stockExists;
}

function getStockDescriptionById($conn, $stockId) {
    // Prepare the SQL statement
    $sql = "SELECT stockDescription FROM stocks WHERE stock_id = ?";
    $stockDescription = "";
    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the stock_id parameter
        $stmt->bind_param("i", $stockId);

        // Execute the statement
        $stmt->execute();

        // Bind the result variable
        $stmt->bind_result($stockDescription);

        // Fetch the result
        if ($stmt->fetch()) {
            // Close the statement
            $stmt->close();
            
            // Return the stock description
            return $stockDescription;
        } else {
            // Close the statement
            $stmt->close();
            
            // Return null if no result found
            return null;
        }
    } else {
        // Handle errors in preparing the statement
        throw new Exception("Error preparing the statement: " . $conn->error);
    }
}

/* END of Stocks Function */

/* Other functions */
function formatPriceChangePercent($priceChangePercent) {
    // Convert the string to a float for comparison
    $value = floatval($priceChangePercent);

    // Check if the value is positive or zero, and add the appropriate symbol
    if ($value >= 0) {
        return '+' . $priceChangePercent;
    } else {
        return $priceChangePercent;
    }
}






