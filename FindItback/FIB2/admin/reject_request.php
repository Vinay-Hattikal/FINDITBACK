<?php
session_start();
include('../db_config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../gmail_management/phpMailer/src/Exception.php';
require '../gmail_management/phpMailer/src/PHPMailer.php';
require '../gmail_management/phpMailer/src/SMTP.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $claim_id = intval($_POST['claim_id']);

    // Validate claim ID
    if ($claim_id <= 0) {
        header("Location: admin_dashboard.php?error=invalid_id");
        exit();
    }

    $conn->begin_transaction();

    try {
        // Fetch claimant details before deleting the claim
        $query = "SELECT users.username AS claimer_name, 
                        --  users.phone AS claimer_phone,
                         users.email AS claimer_email,
                         belongings.name AS belonging_name
                  FROM claims
                  JOIN users ON claims.claimer_id = users.id
                  JOIN belongings ON claims.belonging_id = belongings.id
                  WHERE claims.id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $claim_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Claim not found.");
        }

        $row = $result->fetch_assoc();
        $claimer_name = $row['claimer_name'];
        // $claimer_phone = $row['claimer_phone'];
        $claimer_email = $row['claimer_email'];
        $belonging_name = $row['belonging_name'];

        // Delete the claim from the database
        $delete_claim_query = "DELETE FROM claims WHERE id = ?";
        $delete_claim_stmt = $conn->prepare($delete_claim_query);
        $delete_claim_stmt->bind_param("i", $claim_id);
        if (!$delete_claim_stmt->execute()) {
            throw new Exception("Failed to delete claim.");
        }

        // Commit the transaction
        $conn->commit();

        // Sending rejection email to the claimant
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
        $mail->Subject = "Claim Rejected - Proof Does Not Match";
        $mail->Body = "<!DOCTYPE html>
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
        background-color: rgb(204, 47, 47);
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
        color: #007bff;
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
            <h1>Claim Rejection Notice</h1>
        </div>
        <div class='email-body'>
            <p>Dear $claimer_name,</p>
            <p>We regret to inform you that your claim request for the belonging $belonging_name has been rejected because the proof provided did not match the expected criteria.</p>
            <p>We encourage you to visit the admin section to discuss this further or to submit additional details that may help support your claim.</p>
            <p>Thank you for using <strong>FindItBack</strong>. We are here to assist you in locating your lost items.</p>
            <p>Best regards,<br>FindItBack Team</p>
        </div>
        <div class='email-footer'>
            <p>&copy; 2024 FindItBack. All rights reserved.</p>
            <p><a href='#'>Visit Our Website</a></p>
        </div>
    </div>
</body>
</html>
";

        // Send rejection email
        $mail->send();

        // Redirect with success message
        header("Location: admin_dashboard.php?success=rejected");
    } catch (Exception $e) {
        // Rollback transaction and redirect with error
        $conn->rollback();
        header("Location: admin_dashboard.php?error=" . urlencode($e->getMessage()));
    } finally {
        if (isset($stmt))
            $stmt->close();
        if (isset($delete_claim_stmt))
            $delete_claim_stmt->close();
        $conn->close();
    }
}
?>