<?php
session_start();
include('db_config.php'); // Include database configuration

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get form data
$name = $_POST['name'];
$type = $_POST['type'];
$location_found = $_POST['location_found'];
$date_found = $_POST['date_found'];
$description = $_POST['description'];

// Handle image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_name = uniqid() . "_" . $_FILES['image']['name']; // Generate a unique image name
    $upload_dir = 'belongings_image/';

    // Move uploaded file to the destination
    if (move_uploaded_file($image_tmp_name, $upload_dir . $image_name)) {
        $image_path = $upload_dir . $image_name;

        // Insert data into the database
        $user_id = $_SESSION['user_id']; // User ID from session
        $sql = "INSERT INTO belongings (user_id, name, type, location_found, date_found, description, image_path)
                VALUES ('$user_id', '$name', '$type', '$location_found', '$date_found', '$description', '$image_path')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Belonging uploaded successfully!'); window.history.go(-2);</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Failed to upload the image.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('No image uploaded or there was an error.'); window.history.back();</script>";
}
?>
