<?php
session_start();
include('db_config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch form data
    $belonging_id = $_POST['belonging_id'];
    $claimer_id = $_SESSION['user_id'];
    $lost_location = $_POST['lost_location'];
    $lost_date = $_POST['lost_date'];
    $proof_description = $_POST['proof_description'];
    $proof_image = $_FILES['proof_image'];

    // Handle image upload for proof
    $target_dir = "proof_images/";
    $target_file = $target_dir . basename($proof_image["name"]);
    move_uploaded_file($proof_image["tmp_name"], $target_file);

    // Insert claim request into the claims table
    $stmt = $conn->prepare("INSERT INTO claims (belonging_id, claimer_id, lost_location, lost_date, proof_description, proof_image_path, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("iissss", $belonging_id, $claimer_id, $lost_location, $lost_date, $proof_description, $target_file);

    if ($stmt->execute()) {
        // Echo a success message and redirect to homepage
        echo "<script>alert('Claim request sent.'); 
                    window.history.go(-2);
                </script>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
