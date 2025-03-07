<?php
session_start();
include('../db_config.php'); // Adjust the path as per your file structure

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // Plain password entered by admin

    // Prepare the SQL query to check the admin credentials
    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a matching admin was found
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Verify the password using password_verify
        if ($password==$admin['password']) {
            // Start session and store admin ID
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: admin_dashboard.php");  // Redirect to admin dashboard
            exit();
        } else {
            // Incorrect password
            $_SESSION['error_message'] = 'Incorrect password. Please try again.';
            header("Location: admin_login.php");  // Redirect back to admin login page
            exit();
        }
    } else {
        // Admin not found with that username
        $_SESSION['error_message'] = 'Admin not found. Please try again.';
        header("Location: admin_login.php");  // Redirect back to admin login page
        exit();
    }
}
?>
