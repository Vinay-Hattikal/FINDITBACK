<?php
session_start();
include('db_config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch uploaded belongings by the user
$sql = "SELECT * FROM belongings WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Uploads</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }


        h1 {
            text-align: center;
            margin: 20px 0;
            color: #333;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: calc(33.333% - 20px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .card-content {
            padding: 15px;
        }

        .card-content h3 {
            margin: 0;
            font-size: 1.2em;
            color: #2c3e50;
        }

        .card-content p {
            margin: 10px 0;
            color: #555;
            font-size: 0.9em;
        }

        .card-content span {
            font-weight: bold;
            color: #333;
        }

        @media (max-width: 768px) {
            .card {
                width: calc(50% - 20px);
            }
        }

        @media (max-width: 480px) {
            .card {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <h1>My Uploaded Belongings</h1>
    <?php if ($result->num_rows > 0): ?>
        <div class="card-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <?php if ($row['image_path']): ?>
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Belonging Image">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/300x180?text=No+Image" alt="No Image">
                    <?php endif; ?>
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><span>Type:</span> <?php echo htmlspecialchars($row['type']); ?></p>
                        <p><span>Location Found:</span> <?php echo htmlspecialchars($row['location_found']); ?></p>
                        <p><span>Date Found:</span> <?php echo htmlspecialchars($row['date_found']); ?></p>
                        <p><span>Description:</span> <?php echo htmlspecialchars($row['description']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: #666;">No belongings uploaded yet.</p>
    <?php endif; ?>

</body>

</html>
