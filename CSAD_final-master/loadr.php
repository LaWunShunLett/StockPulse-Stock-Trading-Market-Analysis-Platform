<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>loadr</title>

    <link rel="stylesheet" href="loadr.css">
</head>
<body>
    <div class="loader">
        <div class="tri1"></div>
        <div class="tri2"></div>
        <div class="tri3"></div>
        <div class="tri4"></div>
        <div class="tri5"></div>
        <div class="text">Signing Up</div>

    </div>

    <script>
        setTimeout(function() {
            window.location.href = 'signInSignUp.php'; // Redirect to the home page
        }, 4000);
    </script>
</body>
</html>