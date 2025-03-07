<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/admin_login.css">
</head>
<body>
    <div class="container">
        <h1>Admin Login</h1>

        <!-- Display error message if there's an error in the login process -->
        <?php
        if (isset($_SESSION['error_message'])) {
            echo "<p>" . htmlspecialchars($_SESSION['error_message']) . "</p>";
            unset($_SESSION['error_message']); // Clear the error after displaying
        }
        ?>

        <form action="process_admin_login.php" method="POST">
            <input type="text" name="username" placeholder="Admin Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
