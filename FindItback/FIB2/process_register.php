<?php
include('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone_number = $_POST['phone'];
    $department = $_POST['department'];

    $sql = "INSERT INTO users (username, email, password,phone,department) VALUES (?, ?, ?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $email, $password,$phone_number,$department);

    try {
        if ($stmt->execute()) {
            echo "<script>alert('Register successful.'); window.history.back();</script>";
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        // Check if the error is due to a duplicate entry
        if ($e->getCode() == 1062) { // 1062 is the error code for duplicate entry
            echo "<script>alert('User already exists. Please try with a different email.'); window.history.back();</script>";
            exit();
        } else {
            // For other unexpected errors, you can log them for debugging (optional)
            error_log($e->getMessage());
            echo "<script>alert('An unexpected error occurred. Please try again later.'); window.history.back();</script>";
            exit();
        }
    }
    

    $stmt->close();
}
?>
