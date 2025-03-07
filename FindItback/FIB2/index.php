<?php
// index.php
session_start();

// Check if user is logged in
if (isset($_COOKIE['user_session'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FindItBack</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: rgb(225, 225, 225);
            color: #333;
        }

        html {
    scroll-behavior: smooth;
}


        /* Navbar */
        .navbar {
            background-color: #2C3E50;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }


        .navbar-logo {
            color: #fff;
            font-size: 1.8rem;
            font-weight: 500;
            text-decoration: none;
        }

        .navbar-menu {
            list-style: none;
            display: flex;
            gap: 35px;
        }

        .navbar-menu li {
            margin: 0;
        }

        .navbar-menu a {
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s;
            position: relative;
            /* To position the underline */
            text-decoration: none;
        }

        .navbar-menu a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: white;
            transform: scaleX(0);
            transform-origin: bottom right;
            transition: transform 0.3s ease-out;
        }

        .navbar-menu a:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        .btn-login {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .btn-login:hover {
            background-color: rgb(22, 100, 160);
        }

        /* Hamburger Menu */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
        }

        .hamburger div {
            width: 25px;
            height: 3px;
            background-color: #fff;
        }

        /* Responsive Navbar */
        @media (max-width: 768px) {
            .navbar-menu {
                display: flex;
                flex-direction: column;
                gap: 15px;
                background-color: rgba(0, 0, 0, 0.9);
                position: fixed;
                top: -100%;
                /* Start hidden above the screen */
                left: 0;
                width: 100%;
                max-height: 300px;
                /* Partial height */
                padding: 20px 0;
                align-items: center;
                justify-content: center;
                opacity: 0;
                visibility: hidden;
                transform: translateY(-100%);
                transition: all 0.3s ease-in-out;
            }

            .navbar-menu.active {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
                top: 0;
            }

            .hamburger {
                display: flex;
            }

            .close-btn {
                align-self: flex-end;
                color: #fff;
                font-size: 1.5rem;
                margin: 10px 20px;
                cursor: pointer;
            }

            .close-btn:hover {
                color: #;
            }
        }

        /* Hero Section */
        .hero {
            background: url('https://images.pexels.com/photos/281260/pexels-photo-281260.jpeg?auto=compress&cs=tinysrgb&w=600') no-repeat center center/cover;
            color: #fff;
            text-align: center;
            padding: 100px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            /* Ensures it takes the full height of the viewport */
        }

        .hero-content h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .hero-content .btn-primary {
            display: inline-block;
            padding: 12px 25px;
            font-size: 1rem;
            color: #fff;
            background-color: #007BFF;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
        }

        .hero-content .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .hero-content .btn-primary {
                font-size: 0.9rem;
                padding: 10px 20px;
            }
        }

        @media (max-width: 480px) {
            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-content p {
                font-size: 0.9rem;
            }

            .hero-content .btn-primary {
                font-size: 0.8rem;
                padding: 8px 15px;
            }
        }


        .btn-primary {
            background-color: rgb(26, 93, 188);
            color: #fff;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: rgb(22, 79, 160);
        }

        /* Footer Styles */
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 2em 2em;
            text-align: center;
            position: relative;
            bottom: 0;
            width: 100%;
            font-size: 1em;
        }

        /* Footer Container */
        .footer-container {
            margin-bottom: 1em;
        }

        /* Footer Paragraph */
        .footer p {
            margin: 0.5em 0;
            color: #ccc;
        }

        /* Footer Links */
        .footer a {
            color: rgb(41, 132, 197);
            text-decoration: none;
            font-weight: bold;
            margin: 0 0.5em;
        }

        .footer a:hover {
            color: rgb(53, 174, 95);
        }

        /* Responsive Design for Footer */
        @media (max-width: 768px) {
            .footer {
                padding: 1.5em;
            }

            .footer p {
                font-size: 1em;
            }

            .footer a {
                font-size: 1em;
            }
        }

        /* Mobile View (max-width: 480px) */
        @media (max-width: 480px) {
            .footer {
                padding: 1em;
                font-size: 0.9em;
            }

            .footer p {
                font-size: 0.9em;
            }

            .footer a {
                font-size: 0.9em;
            }
        }


        .about {
            padding: 80px 20px;
            background-color: #f9f9f9;
            text-align: center;
        }

        .about-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .about-content {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            justify-content: center;
            align-items: center;
        }

        .about-text {
            flex: 1 1 60%;
            text-align: left;
        }

        .about-text p {
            font-size: 1.1rem;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .about-text ul {
            list-style-type: disc;
            margin-left: 20px;
        }

        .about-text ul li {
            margin-bottom: 10px;
        }

        body::-webkit-scrollbar{
            display: none;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="#" class="navbar-logo" onclick="location.reload(); return false;">FindItBack</a>

        <div class="hamburger" onclick="toggleMenu()">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <ul class="navbar-menu" id="navbar-menu">
            <span class="close-btn" onclick="toggleMenu()"></span>
            <li><a href="#home" onclick="closeMenu()">Home</a></li>
            <li><a href="#about" onclick="closeMenu()">About</a></li>
            <?php if (!isset($_SESSION['user_logged_in'])): ?>
                <li><a href="login.php" class="btn-login">Login</a></li>
            <?php else: ?>
                <li><a href="dashboard.php" class="btn-login">Dashboard</a></li>
                <li><a href="logout.php" class="btn-login">Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Lost Something? We Can Help You Find It!</h1>
            <h1>Found Something? Report it here to help others reclaim their belongings!</h1>

            <p>Claim lost belongings by submitting a request and report belongings you’ve found in your college campus.
            </p>
            <a href="login.php" class="btn-primary">Learn More</a>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="about-container">
            <h2>About FindItBack</h2>
            <div class="about-content">
                <div class="about-text">
                    <p>
                        FindItBack is a dedicated platform designed to help students to find their
                        lost belongings on campus with ease.
                        Whether you've misplaced a gadget, left beneath the bench, or found someone's ID card, and more.
                        FindItBack is
                        here to bridge the gap between
                        the finder and the owner. By connecting the person who found an item with the one who has
                        requested its return, FindItBack ensures
                        a seamless and trustworthy process for reuniting lost belongings with their rightful owners.
                    </p>
                    <p>
                        <strong>Why Choose FindItBack?</strong>
                    </p>
                    <ul>
                        <li>Easy-to-use interface for uploading and searching for items.</li>
                        <li>Secure system for submitting claims with proof of ownership.</li>
                        <li>Real-time Gmail notifications to keep you updated on claims.</li>
                        <li>Separate dashboards for finders and claimants to manage activity.</li>
                        <li>Designed exclusively for college campuses to build trust and connectivity.</li>
                    </ul>
                    <p>
                        Join us in creating a reliable and organized system where lost items find their way back to
                        their rightful owners. Together,
                        let’s make lost and found stress-free and efficient!
                    </p>
                </div>
            </div>
        </div>
    </section>
    


    <footer class="footer" id="contact">
        <p>&copy; 2024 FindItBack. All rights reserved. | <a href="privacy_policy.php">Privacy Policy</a> | <a
                href="terms_services.php">Terms of
                Service</a></p>
    </footer>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('navbar-menu');
            menu.classList.toggle('active');
        }

        function closeMenu() {
            const menu = document.getElementById('navbar-menu');
            menu.classList.remove('active');
        }

        // Close the navbar if clicking outside
        document.addEventListener('click', (event) => {
            const menu = document.getElementById('navbar-menu');
            const hamburger = document.querySelector('.hamburger');
            if (!menu.contains(event.target) && !hamburger.contains(event.target)) {
                menu.classList.remove('active');
            }
        });
    </script>
</body>

</html>