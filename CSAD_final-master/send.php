<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';

if(isset($_POST["send"])){
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'stockpulse0@gmail.com'; // Your Gmail address
        $mail->Password = 'vxli tocp rndc nwoz'; // Your Gmail app password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('stockpulse0@gmail.com');
        $mail->addAddress('stockpulse0@gmail.com'); // Replace with your actual admin email address

        $mail->isHTML(true);
        $mail->Subject = 'Message from ' . $_POST["email"]; // Include user's email in the subject
        $mail->Body = '
            
            <p><strong>Name:</strong> ' . $_POST["name"] . '</p>
            <p><strong>From:</strong> ' . $_POST["email"] . '</p>
            <p><strong>Message:</strong> ' . nl2br($_POST['message']) . '</p>
        '; // Include user's email, subject, and message in the body

        $mail->send();

        echo "
        <script>
        alert('Sent Successfully');
        document.location.href='support.php';
        </script>
        ";
    } catch (Exception $e) {
        echo "
        <script>
        alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');
        </script>
        ";
    }
}
?>