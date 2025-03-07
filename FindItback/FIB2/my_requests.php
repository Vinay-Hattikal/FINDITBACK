<?php
session_start();
include('db_config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch claims made by the logged-in user
$sql = "
    SELECT 
        claims.id AS claim_id,
        belongings.name AS belonging_name,
        claims.lost_location,
        claims.lost_date,
        claims.proof_description,
        claims.proof_image_path,
        claims.status,
        claims.created_at
    FROM 
        claims
    JOIN 
        belongings ON claims.belonging_id = belongings.id
    WHERE 
        claims.claimer_id = ?
    ORDER BY 
        claims.created_at DESC
";
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
    <title>My Requests</title>
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

        .status {
            font-weight: bold;
            text-transform: capitalize;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
        }

        .status.pending {
            background-color: #f0ad4e;
            color: white;
        }

        .status.approved {
            background-color: #5cb85c;
            color: white;
        }

        .status.rejected {
            background-color: #d9534f;
            color: white;
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
    <h1>My Requests</h1>
    <?php if ($result->num_rows > 0): ?>
        <div class="card-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <div class="card-content">
                        <h3>Belonging: <?php echo htmlspecialchars($row['belonging_name']); ?></h3>
                        <p><span>Lost Location:</span> <?php echo htmlspecialchars($row['lost_location']); ?></p>
                        <p><span>Lost Date:</span> <?php echo htmlspecialchars($row['lost_date']); ?></p>
                        <p><span>Proof Description:</span> <?php echo htmlspecialchars($row['proof_description']); ?></p>
                        <?php if ($row['proof_image_path']): ?>
                            <p><span>Proof Image:</span></p>
                            <img src="<?php echo htmlspecialchars($row['proof_image_path']); ?>" alt="Proof Image" style="width: 100%; max-height: 180px; object-fit: cover;">
                        <?php endif; ?>
                        <p><span>Status:</span> 
                            <span class="status <?php echo htmlspecialchars($row['status']); ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </p>
                        <p><span>Submitted At:</span> <?php echo htmlspecialchars($row['created_at']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: #666;">No requests submitted yet.</p>
    <?php endif; ?>
</body>

</html>
