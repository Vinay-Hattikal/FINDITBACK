<?php
session_start();
include('db_config.php');

// Redirect if already logged in
if (isset($_SESSION['user_id']) && isset($_COOKIE['user_session'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindItBack - Student Login & Register</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Reset */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            /* Ensure space between logo and menu */
            align-items: center;
            /* Vertically align items */
            padding: 10px 5%;
            /* Adjust padding to fit different screen sizes */
            background-color: #2C3E50;
            color: white;
            font-family: Arial, sans-serif;
            width: 100%;
            box-sizing: border-box;
            /* Include padding in width calculation */
        }

        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
        }


        .navbar .logo h1 {
            font-size: 18px;
            margin: 0;
        }

        .container {
            background: #ffffff;
            padding: 20px 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }

        h1 {
            color: rgb(33, 90, 148);
            font-size: 36px;
            margin-bottom: 10px;
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
            width: 100%;
            box-sizing: border-box;
        }

        .input-group i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .input-group input {
            width: 100%;
            padding: 10px 10px 10px 40px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }

        .input-group input:focus {
            border-color: rgb(33, 90, 148);
            ;
        }

        button {
            background-color: rgb(33, 90, 148);
            ;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: rgb(10, 33, 117);
        }

        .link {
            margin-top: 10px;
        }

        .link a {
            color: rgb(33, 90, 148);

            text-decoration: none;
            transition: color 0.3s;
        }

        .link a:hover {
            color: #388E3C;
        }

        /* Toggle Forms */
        #register-form {
            display: none;
        }

        /* Admin Link */
        .link a:last-child {
            font-size: 14px;
            margin-top: 10px;
            display: inline;
        }

        .footer {
            background-color: #f3f4f6;
            text-align: center;
            padding: 30px;
            margin-top: 20px;
            font-size: 14px;
            color: #333;
            box-shadow: 0 -1px 5px rgba(0, 0, 0, 0.1);
        }

        .footer p {
            margin: 0;
            padding: 0;
        }

        .footer a {
            color: rgb(33, 90, 148);
            ;
            text-decoration: none;
            margin: 0 5px;
            transition: color 0.3s;
        }

        .footer a:hover {
            color: #388E3C;
        }

        /* Responsive Design */
        @media (max-width: 500px) {
            .container {
                padding: 15px 20px;
            }

            h1 {
                font-size: 28px;
            }

            h2 {
                font-size: 20px;
            }

            button {
                font-size: 14px;
                padding: 8px 16px;
            }

            .footer {
                background-color: #f3f4f6;
                text-align: center;
                padding: 15px;
                margin-top: 20px;
                font-size: 14px;
                color: #333;
                box-shadow: 0 -1px 5px rgba(0, 0, 0, 0.1);
            }

            .navbar {
                flex-direction: column;
                /* Stack logo and menu vertically */
                align-items: flex-start;
                /* Align items to the left */
                padding: 20px;
                /* Reduce padding for smaller screens */
            }
        }

        /* Style for Admin link */
        .link-admin a {
            background-color: rgb(56, 163, 62);
            /* Red background color */
            color: #fff;
            /* White text color */
            padding: 10px 19px;
            font-size: 1.1rem;
            text-decoration: none;
            /* Remove default underline */
            border-radius: 5px;
            /* Rounded corners */
            transition: background-color 0.3s, transform 0.3s;
            /* Transition for hover effect */
        }

        .link-admin a:hover {
            background-color: rgb(47, 121, 64);
            /* Darker red on hover */
            transform: scale(1.05);
            /* Slightly enlarge the button */
        }

        /* Optional: Add a shadow to make the button more prominent */
        .link-admin a:active {
            transform: scale(1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }


        .input-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #f9f9f9;
            color: #333;
            outline: none;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .input-group select:focus {
            border-color: rgb(33, 90, 148);
            /* Highlight border on focus */
            box-shadow: 0 0 5px rgba(33, 90, 148, 0.5);
            /* Add subtle glow effect */
            background-color: #fff;
            /* Brighten background */
        }

        .input-group select option {
            padding: 10px;
            /* Add padding to each option */
            font-size: 14px;
            /* Adjust font size */
        }
    </style>


    <script>
        function toggleForm(formType) {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');

            if (formType === 'register') {
                loginForm.style.transform = 'translateY(20px)';
                loginForm.style.opacity = '0';
                setTimeout(() => {
                    loginForm.style.display = 'none';
                    registerForm.style.display = 'block';
                    registerForm.style.opacity = '1';
                    registerForm.style.transform = 'translateY(0)';
                }, 300);
            } else {
                registerForm.style.transform = 'translateY(20px)';
                registerForm.style.opacity = '0';
                setTimeout(() => {
                    registerForm.style.display = 'none';
                    loginForm.style.display = 'block';
                    loginForm.style.opacity = '1';
                    loginForm.style.transform = 'translateY(0)';
                }, 300);
            }
        }
    </script>
</head>

<body>
    <div class="navbar">
        <div class="logo">FindItBack</div>
    </div>
    <!-- Login Form -->
    <div class="container" id="login-form">
        <h1>FindItBack</h1>
        <h2>Login</h2>
        <form action="process_login.php" method="POST">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="mail" name="email" placeholder="Email" pattern="[a-z0-9._%+-]+@gmail\.com" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Sign-in</button>
        </form>
        <div class="link">
            <p>Don't have an account? <a href="javascript:void(0);" onclick="toggleForm('register')">Register</a></p>
        </div>
        <div class="link-admin">
            <a href="admin/admin_login.php">Admin</a>
        </div>
    </div>

    <!-- Register Form -->
    <div class="container" id="register-form" style="display:none;">
        <h2>Registration</h2>

        <form action="process_register.php" method="POST"> 
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required> <!--username-->
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" title="Please enter a valid email" required> <!--Email-->
            </div>

            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="tel" name="phone" placeholder="Phone Number" required pattern="[0-9]{10}"
                    title="Please enter a valid 10-digit phone number"> <!--Phone-->
            </div>

            <div class="input-group">
                <select name="department" required> <!--Department-->
                    <option value="">Select Department</option>
                    <option value="Information Science Engineering">Information Science Engineering</option>
                    <option value="Computer Science Engineering">Computer Science Engineering</option>
                    <option value="Mechanical Engineering">Mechanical Engineering</option>
                    <option value="Civil Engineering">Civil Engineering</option>
                    <option value="Electronics and Communications">Electronics and Communications</option>
                    <option value="Bio Technology">Bio Technology</option>
                    <option value="Electrical and Electronics Engineering">Electrical and Electronics Engineering
                    </option>
                    <option value="Industrial Production">Industrial Production</option>
                </select>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required> <!--password-->
            </div>

            <button type="submit">Sign-up</button> <!--register account button-->
        </form>

        <div class="link">
            <p>Already have an account? <a href="javascript:void(0);" onclick="toggleForm('login')">Login</a></p>
        </div>
    </div>
    <footer class="footer">
        <p>&copy; 2024 FindItBack. All rights reserved. | <a href="privacy_policy.php">Privacy Policy</a> | <a
                href="terms_services.php">Terms of
                Service</a></p>
    </footer>
</body>

</html>