<?php
session_start();
include('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if the user exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Set cookie to keep the user logged in for a week
            $cookie_data = base64_encode(json_encode(['id' => $user['id'], 'username' => $user['username']]));
            setcookie('user_session', $cookie_data, time() + (7 * 24 * 60 * 60), "/", "", true, true); // 1 week duration

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password.');</script>";
            echo "<script>window.location.href = window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('User not found.');</script>";
        echo "<script>window.location.href = window.history.back();</script>";
        exit();
    }
    $stmt->close();
}
?>
