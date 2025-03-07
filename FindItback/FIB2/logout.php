<?php
session_start();

// Destroy session
session_unset();
session_destroy();

// Clear the cookie
setcookie('user_session', '', time() - 3600, "/", "", true, true);

// Redirect to login page
header("Location: index.php");
exit();
?>