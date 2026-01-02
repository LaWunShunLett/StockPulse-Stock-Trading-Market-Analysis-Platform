<!DOCTYPE html>
<?php 
 session_start(); // Start the session
 if (!isset($_SESSION['userId'])) {
     header("Location: error.php"); // Redirect to error page
     exit();
 }
   $company = isset($_GET['company']) ? urldecode($_GET['company']) : null;
   
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy/Sell Form</title>
    <link rel="stylesheet" href="css/buy_sell.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <form id="tradeForm" action="buy_sell_backend.php?company=<?php echo htmlspecialchars($company)?>" method="post">
        <label for="action">Action:</label>
        <select id="action" name="action" required>
            <option value="">Select an option</option>
            <option value="Buy">Buy</option>
            <option value="Sell">Sell</option>
        </select>
        
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" step="1" required placeholder="Enter quantity">

        <button type="submit" id="submitButton"><i class="fas"></i> <span>Submit</span></button>
        <p style="color:red"><p style="color:red"><?php echo isset($_GET["error"]) ? htmlspecialchars($_GET["error"]) : ''; ?></p> <!-- error message --></p>    <!-- error message -->
    </form>
    

    <script>
        document.getElementById('action').addEventListener('change', function() {
            let action = document.getElementById('action').value;
            let submitButton = document.getElementById('submitButton');
            let buttonIcon = submitButton.querySelector('i');
            let buttonText = submitButton.querySelector('span');
            
            if (action === 'Buy') {
                buttonIcon.className = 'fas fa-shopping-cart';
                buttonText.textContent = 'Buy';
                submitButton.style.backgroundColor = '#1abc9c'; // Buy color
            } else if (action === 'Sell') {
                buttonIcon.className = 'fas fa-dollar-sign';
                buttonText.textContent = 'Sell';
                submitButton.style.backgroundColor = '#e53935'; // Sell color
            } else {
                buttonIcon.className = '';
                buttonText.textContent = 'Submit';
                submitButton.style.backgroundColor = ''; // Default color
            }
        });

        document.getElementById('tradeForm').addEventListener('submit', function(event) {
            let action = document.getElementById('action').value;
            let quantity = document.getElementById('quantity').value;
            let quantityInput = document.getElementById('quantity');
            quantityInput.placeholder = "Enter quantity"; // Reset placeholder

            if (!action) {
                alert('Please select an action (Buy or Sell).');
                event.preventDefault();
                return;
            }

            if (!quantity || quantity <= 0) {
                quantityInput.placeholder = 'Required!';
                quantityInput.value = ''; // Clear the input value
                event.preventDefault();
                return;
            }

            // If form is valid, it will be submitted automatically
        });
    </script>

</body>
</html>
