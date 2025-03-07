<?php
session_start();
include('db_config.php');

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Initialize search query 
$searchQuery = "";

// Handle search functionality
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $sql = "SELECT b.* FROM belongings b
            LEFT JOIN claims c ON b.id = c.belonging_id
            WHERE c.belonging_id IS NULL
            AND b.name LIKE '%$searchQuery%'";
} else {
    $sql = "SELECT b.* FROM belongings b
            LEFT JOIN claims c ON b.id = c.belonging_id
            WHERE c.belonging_id IS NULL";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find My Belonging</title>
    <style>
        /* Global Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Body Styles */
        body {
            background-color: #f5f5f5;
            color: #333;
        }

        /* Main Content */
        .dashboard {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Search Bar */
        .search-bar {
            text-align: center;
            margin-bottom: 30px;
        }

        /* Form Layout */
        .search-bar form {
            display: flex;
            flex-direction: column;
            /* Stack input and button vertically */
            justify-content: center;
            align-items: center;
        }

        /* Input Field */
        .search-bar input[type="text"] {
            width: 100%;
            /* Full width on smaller screens */
            max-width: 600px;
            /* Limit width on larger screens */
            padding: 12px 20px;
            font-size: 16px;
            border: 2px solid #2c3e50;
            border-radius: 8px;
            outline: none;
            margin-bottom: 15px;
            /* Space between input and button */
            transition: all 0.3s;
        }

        .search-bar input[type="text"]:focus {
            border-color: #2c3e50;
        }

        /* Button */
        .search-bar button {
            padding: 12px 30px;
            font-size: 16px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            /* Make button take full width on smaller screens */
            max-width: 600px;
            /* Limit width on larger screens */
        }

        .search-bar button:hover {
            background-color: #2c3e50;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .search-bar input[type="text"] {
                width: 90%;
                /* Input slightly smaller on medium screens */
            }

            .search-bar button {
                width: 90%;
                /* Button slightly smaller on medium screens */
            }
        }

        @media (max-width: 480px) {
            .search-bar input[type="text"] {
                width: 90%;
                /* Input field takes up most of the screen width on small screens */
            }

            .search-bar button {
                width: 90%;
                /* Button width also takes up most of the screen width */
            }
        }

        /* Cards Layout */
        .belongings {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            /* Flexible columns */
            gap: 20px;
            /* Adjust gap between cards */
            padding: 20px;
        }

        /* Belonging Card */
        .belonging-item {
            background-color: white;
            border: 2px solid #ddd;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .belonging-item:hover {
            transform: translateY(-10px);
            box-shadow: 0px 15px 40px rgba(0, 0, 0, 0.2);
        }

        .belonging-item img {
            max-width: 100%;
            height: 200px;
            /* Adjust image size for a balanced layout */
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        .belonging-item h3 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 10px;
        }

        .belonging-item p {
            color: #555;
            margin-bottom: 8px;
        }

        /* Description Box */
        .belonging-item .description {
            overflow-y: auto;
            /* Enable scrolling if description is long */
            max-height: 100px;
            /* Limit the height to fit the card */
            margin-bottom: 10px;
        }

        .claim-btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #1854bc;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .claim-btn:hover {
            background-color: #1976D2;
        }

        .belonging-item p em {
            color: #f44336;
        }

        /* Mobile responsiveness for cards */
        @media (max-width: 768px) {
            .belonging-item {
                padding: 15px;
                /* Reduce padding for smaller screens */
            }

            .belonging-item img {
                height: 180px;
                /* Smaller images for smaller screens */
            }
        }

        @media (max-width: 480px) {
            .belonging-item {
                padding: 10px;
                /* Even smaller padding on smaller screens */
            }

            .belonging-item img {
                height: 150px;
                /* Smaller images for mobile */
            }
        }

        /* Heading Styles */
        h1 {
            font-size: 2.5em;
            /* Make the font size large */
            font-weight: bold;
            /* Make the font bold */
            color: #333;
            /* Dark text color for contrast */
            text-align: center;
            /* Center the heading horizontally */
            margin-top: 20px;
            /* Add some space from the top of the page */
            margin-bottom: 30px;
            /* Space below the heading */
            padding: 0 20px;
            /* Padding on left and right for smaller screens */
            font-family: 'Arial', sans-serif;
            /* Use a clean, readable font */
        }

        /* Adjust heading positioning for different screen sizes */
        @media (max-width: 768px) {
            h1 {
                font-size: 2em;
                /* Slightly smaller font size on medium screens */
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.8em;
                /* Even smaller font size for small screens */
                margin-top: 10px;
                /* Reduce top margin on smaller screens */
            }
        }
    </style>
</head>

<body>
    <div class="dashboard">
        <h1>Unclaimed Belongings</h1>

        <!-- Search Bar -->
        <div class="search-bar">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by name..."
                    value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Belongings List -->
        <?php if ($result->num_rows > 0): ?>
            <div class="belongings">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="belonging-item">
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Belonging Image"
                            class="belonging-image">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><strong>Type:</strong> <?php echo htmlspecialchars($row['type']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location_found']); ?></p>
                        <div class="description">
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                        </div>
                        <p><strong>Date:</strong><?php echo htmlspecialchars($row['date_found']); ?></p>

                        <!-- Check if the current user is not the uploader -->
                        <?php if ($row['user_id'] != $_SESSION['user_id']): ?>
                            <a href="claim_details.php?belonging_id=<?php echo $row['id']; ?>" class="claim-btn">Claim</a>
                        <?php else: ?>
                            <p style="color: red;"><em>Unclaimable to uploader</em></p>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No unclaimed belongings found.</p>
        <?php endif; ?>
    </div>
</body>

</html>