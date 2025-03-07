<?php
session_start();
include('../db_config.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $claim_id = intval($_POST['claim_id']);

    // Validate claim ID
    if ($claim_id <= 0) {
        header("Location: admin_dashboard.php?error=invalid_claim_id");
        exit();
    }

    $conn->begin_transaction();

    try {
        // Fetch claimant details
        $query = "SELECT claims.belonging_id, 
                         users.username AS claimer_name, 
                         users.phone AS claimer_phone,
                         users.email AS claimer_email 
                  FROM claims 
                  JOIN users ON claims.claimer_id = users.id 
                  WHERE claims.id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $claim_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Claim not found.");
        }

        $row = $result->fetch_assoc();
        $belonging_id = $row['belonging_id'];
        $claimer_name = $row['claimer_name'];
        $claimer_phone = $row['claimer_phone'];
        $claimer_email = $row['claimer_email'];

        // Fetch uploader details (name, phone number)
        $uploader_query = "SELECT 
                        users.username AS uploader_name, 
                        users.phone AS uploader_phone, 
                        users.department AS uploader_department, 
                        belongings.name AS belonging_name 
                   FROM users 
                   JOIN belongings ON belongings.user_id = users.id 
                   WHERE belongings.id = ?";

        $uploader_stmt = $conn->prepare($uploader_query);
        $uploader_stmt->bind_param("i", $belonging_id);
        $uploader_stmt->execute();
        $uploader_result = $uploader_stmt->get_result();

        if ($uploader_result->num_rows === 0) {
            throw new Exception("Uploader details not found.");
        }

        $uploader_row = $uploader_result->fetch_assoc();
        $uploader_name = $uploader_row['uploader_name'];
        $uploader_phone = $uploader_row['uploader_phone'];
        $belonging_name = $uploader_row['belonging_name'];
        $uploader_department = $uploader_row['uploader_department'];

        // Delete the claim
        $delete_claim_query = "DELETE FROM claims WHERE id = ?";
        $delete_claim_stmt = $conn->prepare($delete_claim_query);
        $delete_claim_stmt->bind_param("i", $claim_id);
        if (!$delete_claim_stmt->execute()) {
            throw new Exception("Failed to delete claim.");
        }

        // Delete the belonging
        $delete_belonging_query = "DELETE FROM belongings WHERE id = ?";
        $delete_belonging_stmt = $conn->prepare($delete_belonging_query);
        $delete_belonging_stmt->bind_param("i", $belonging_id);
        if (!$delete_belonging_stmt->execute()) {
            throw new Exception("Failed to delete belonging.");
        }

        // Commit the transaction
        $conn->commit();

        // Redirect to email script with both claimant and uploader details
        header("Location: ../gmail_management/accept.php?name=" . urlencode($claimer_name) . 
               "&phone=" . urlencode($claimer_phone) . 
               "&email=" . urlencode($claimer_email) .
               "&uploader_name=" . urlencode($uploader_name) .
               "&uploader_phone=" . urlencode($uploader_phone).
                "&belonging_name=" . urlencode($belonging_name).
                "&uploader_department=" .urlencode($uploader_department)); 
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: admin_dashboard.php?error=" . urlencode($e->getMessage()));
    } finally {
        $stmt->close();
        $delete_claim_stmt->close();
        $delete_belonging_stmt->close();
        $conn->close();
    }
}
?>
