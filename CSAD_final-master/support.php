<?php
 session_start(); // Start the session
 if (!isset($_SESSION['userId'])) {
     header("Location: error.php"); // Redirect to error page
     exit();
 }
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support</title>
    <link rel="stylesheet" href="css/support.css">
    <script src="https://smtpjs.com/v3/smtp.js"></script>
    <script src="js/support.js"></script>
    
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="contact-form">
            <h2>Contact Form</h2>
            <form action="send.php" method="post">
                Name:<br>
                <input type="text" name="name" value=""><br>
                Email:<br>
                <input type="email" name="email" value=""><br>
                Message:<br>
                <textarea name="message" rows="5"></textarea><br>
                <button type="submit" name="send">Send Message</button>
            </form>
        </div>
        <div class="faq">
            <h2>Help and FAQ</h2>
            <p><strong>How can I reset my password?</strong><br>
            To reset your password, go to the login page and click on the "Forgot Password" link. Follow the instructions to reset your password.</p>
            <p><strong>How can I contact support?</strong><br>
            You can contact support by filling out the contact form above or by emailing us at support@example.com.</p>
        </div>
        <div id="responseMessage" style="display:none;">
    <p>Message received! Thank you for contacting us.</p>
    <a href="index.php">Go back to home page</a>
</div>
</body>
</html>
