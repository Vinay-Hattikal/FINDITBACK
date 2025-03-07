<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpMailer/src/Exception.php';
require 'phpMailer/src/PHPMailer.php';
require 'phpMailer/src/SMTP.php';

// Retrieve details from the GET parameters
if (isset($_GET['name']) && isset($_GET['phone']) && isset($_GET['email']) && isset($_GET['uploader_name']) && isset($_GET['uploader_phone']) && isset($_GET['belonging_name'])) {
    $claimer_name = htmlspecialchars($_GET['name']);
    $claimer_phone = htmlspecialchars($_GET['phone']);
    $claimer_email = htmlspecialchars($_GET['email']);
    $uploader_name = htmlspecialchars($_GET['uploader_name']);
    $uploader_phone = htmlspecialchars($_GET['uploader_phone']);
    $belonging_name = htmlspecialchars($_GET['belonging_name']);
    $uploader_department = htmlspecialchars($_GET['uploader_department']);

    try {
        $mail = new PHPMailer(true);

        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'finditbackplatform@gmail.com';
        $mail->Password = 'keuh igbs jwtt svrc';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Sender and recipient
        $mail->setFrom('finditbackplatform@gmail.com', 'FindItBack');
        $mail->addAddress($claimer_email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Claim Approved";
        $mail->Body = "
<!DOCTYPE html>
<html>
<head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }
    .email-container {
        max-width: 600px;
        margin: 20px auto;
        background-color: #ffffff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .email-header {
        background-color:rgb(44, 177, 32);
        color: #ffffff;
        padding: 20px;
        text-align: center;
    }
    .email-header h1 {
        margin: 0;
        font-size: 24px;
    }
    .email-body {
        padding: 20px;
        color: #333333;
        line-height: 1.6;
    }
    .email-body p {
        margin: 0 0 15px;
    }
    .email-body strong {
        color:rgb(27, 140, 40);
    }
    .email-footer {
        background-color: #f4f4f4;
        padding: 15px;
        text-align: center;
        font-size: 14px;
        color: #666666;
    }
    .email-footer a {
        color: #007bff;
        text-decoration: none;
    }
</style>
</head>
<body>
    <div class='email-container'>
        <div class='email-header'>
            <h1>Approved</h1>
        </div>
        <div class='email-body'>
            <p>Hi $claimer_name,</p>
            <P>Your claim request for the belonging $belonging_name has been approved by Admin and is currently with <strong>$uploader_name</strong>. To retrieve your lost item, please reach out to them directly.</p><hr>
            <h3>Uploader details : </h3>
            <p>Name : <strong>$uploader_name</strong></p>
            <p>Phone. No : <strong>$uploader_phone</strong></p>
            <p>Department : <strong>$uploader_department</strong></p>
            <hr>
            <p>Thank you for using <strong>FindItBack</strong>. We hope this helps you recover your belongings!</p>
        </div>
        <div class='email-footer'>
            <p>&copy; 2024 FindItBack. All rights reserved.</p>
            <p><a href='#'>Visit Our Website</a></p>
        </div>
    </div>
</body>
</html>";


        // Send email
        $mail->send();

        echo "<script>alert('Email sent successfully to $claimer_email.');</script>";
        header("Location: ../admin/admin_dashboard.php?success=accepted");
    } catch (Exception $e) {
        echo "<p>Error sending email: " . $mail->ErrorInfo . "</p>";
    }
} else {
    echo "<p>Error: Missing claimant or uploader details.</p>";
}
?>