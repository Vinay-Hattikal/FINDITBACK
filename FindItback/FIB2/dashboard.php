<?php
session_start();
include('db_config.php');

// Check if the user is logged in via session or cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_session'])) {
    $cookie_data = json_decode(base64_decode($_COOKIE['user_session']), true);

    if ($cookie_data && isset($cookie_data['id'])) {
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE id = ?");
        $stmt->bind_param("i", $cookie_data['id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            setcookie('user_session', $_COOKIE['user_session'], time() + (7 * 24 * 60 * 60), "/", "", true, true);
        } else {
            setcookie('user_session', '', time() - 3600, "/", "", true, true);
            header("Location: index.php");
            exit();
        }
    } else {
        header("Location: index.php");
        exit();
    }
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Body Style */
        body {
            background-image: url("https://png.pngtree.com/thumb_back/fh260/background/20220906/pngtree-cool-wave-liquid-background-for-landing-page-website-image_1463455.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: flex-start;
        }

        /* Navbar Styles */
        nav {
            background: #2c3e50;
            color: white;
            padding: 1em 2em;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        /* Logo Style */
        .logo {
            font-size: 1.5em;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        /* Navbar Links Container */
        .nav-links {
            display: flex;
            gap: 1em;
            transition: all 0.3s ease-in-out;
            /* Smooth opening/closing animation */
            opacity: 1;
            visibility: visible;
        }

        /* Navbar Links */
        .nav-links a {
            text-decoration: none;
            color: white;
            background:rgb(24, 84, 188);
            padding: 0.5em 1em;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s ease-in-out;
        }

        .nav-links a:hover {
            background: #e84141;
        }

        /* Menu Toggle Button (For Mobile) */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5em;
            cursor: pointer;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {

            /* Nav Links for Mobile */
            .nav-links {
                display: flex;
                flex-direction: column;
                background: rgba(0, 0, 0, 0.82);
                position: absolute;
                top: 100%;
                right: 0;
                width: 100%;
                text-align: center;
                padding: 1em 0;
                opacity: 0;
                /* Initially hidden */
                visibility: hidden;
            }

            /* Show Nav Links on Toggle */
            .nav-links.show {
                opacity: 1;
                visibility: visible;
            }

            /* Menu Toggle Button on Mobile */
            .menu-toggle {
                display: block;
            }
        }

        /* Adjustments for Smaller Screens */
        @media (max-width: 480px) {
            .logo {
                font-size: 1.2em;
            }

            .nav-links a {
                font-size: 0.9em;
                padding: 0.4em 0.8em;
            }
        }

        /* Responsive Dashboard Layout */
        .dashboard {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 3em 2em;
            flex: 1;
        }

        /* Content Section */
        .content {
            max-width: 600px;
        }

        /* Heading in Content */
        .content h1 {
            font-size: 2.5em;
            color: #333;
        }

        /* Paragraph in Content */
        .content p {
            margin: 1em 0;
            color: #666;
            font-size: 1.2em;
        }

        /* Button Group in Content */
        .buttons {
            margin-top: 2em;
        }

        /* Main Buttons */
        .btn {
            text-decoration: none;
            background: rgb(24, 84, 188);f2;
            color: white;
            padding: 0.8em 1.5em;
            border-radius: 5px;
            font-weight: bold;
            margin-right: 1em;
            transition: background 0.2s ease-in-out;
        }

        .btn:hover {
            background: #3a4bdb;
        }

        /* Side Image Styling */
        .side-image {
            max-width: 50%;
            height: auto;
        }

        /* Responsive Adjustments for Dashboard */
        @media (max-width: 768px) {
            .dashboard {
                flex-direction: column;
                text-align: center;
            }

            .side-image {
                max-width: 80%;
                margin-top: 2em;
            }

            .buttons {
                display: flex;
                flex-direction: column;
                gap: 1em;
            }

            .btn {
                width: 80%;
                text-align: center;
                margin: 0.5em auto;
            }
        }
    </style>
</head>

<body>
    <nav>
        <a href="dashboard.php" class="logo">FindItBack</a>
        <div class="nav-links" id="nav-links">
            <a href="my_uploads.php" class="btn">My uploads</a>
        <a href="my_requests.php" class="btn">My requests</a>
        <a href="logout.php" class="btn">Logout</a>
        </div>
        <button class="menu-toggle" id="menu-toggle" aria-label="Toggle navigation">&#9776;</button>
    </nav>

    <div class="dashboard">
        <div class="content">
            <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>
            <p>What would you like to do today?</p>

            <div class="buttons">
                <a href="upload_belongings.php" class="btn">Upload Found Belonging</a>
                <a href="claim_belonging.php" class="btn">Find My Belonging</a>
            </div>
        </div>

        <img src="https://scannero.io/blog/wp-content/uploads/2022/04/track-my-lost-phone-by-number.jpg"
            alt="Side Image" class="side-image">
    </div>
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const navLinks = document.getElementById('nav-links');

        // Toggle Navbar
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('show');
        });

        // Close Navbar on Outside Click
        document.addEventListener('click', (event) => {
            if (!navLinks.contains(event.target) && !menuToggle.contains(event.target)) {
                navLinks.classList.remove('show');
            }
        });

    </script>
    
</body>

</html>