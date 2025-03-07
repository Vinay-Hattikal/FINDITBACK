<!DOCTYPE html>
<html lang="en">


<?php
session_start();
include('../db_config.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all pending claims along with belonging and user details
$sql = "SELECT claims.*, 
               belongings.name AS belonging_name, 
               belongings.image_path AS belonging_image, 
               uploader.username AS uploader_name, 
               belongings.description AS uploader_description,
               claimer.username AS claimer_name, 
               claims.proof_description AS claimer_description, 
               claims.proof_image_path 
        FROM claims
        JOIN belongings ON claims.belonging_id = belongings.id
        JOIN users AS uploader ON belongings.user_id = uploader.id 
        JOIN users AS claimer ON claims.claimer_id = claimer.id
        WHERE claims.status = 'pending'";

$result = $conn->query($sql);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pending Claims</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #e3f2fd;
            padding: 20px;
        }

        .navbar {
            background: #0288d1;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .navbar h1 {
            font-size: 1.8rem;
        }

        .navbar .logout-btn {
            background: #d32f2f;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .navbar .logout-btn:hover {
            background: #b71c1c;
        }

        .admin-dashboard {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background: #0288d1;
            color: white;
            text-transform: uppercase;
        }

        td img {
            width: 120px;
            height: auto;
            border-radius: 5px;
            transition: transform 0.3s;
        }

        td img:hover {
            transform: scale(1.1);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .approve-btn,
        .reject-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: 0.3s;
        }

        .approve-btn {
            background: #4CAF50;
            color: white;
        }

        .approve-btn:hover {
            background: #388e3c;
        }

        .reject-btn {
            background: #f44336;
            color: white;
        }

        .reject-btn:hover {
            background: #d32f2f;
        }

        .loading {
            display: none;
            color: #555;
            font-style: italic;
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .no-claims {
            text-align: center;
            color: #777;
            font-size: 1.2rem;
            margin-top: 30px;
        }

        @media (max-width: 768px) {

            table,
            th,
            td {
                display: block;
                width: 100%;
                text-align: left;
            }

            th,
            td {
                padding: 10px;
            }
        }
    </style>
    <script>
        function showLoading(button) {
            const loadingText = document.createElement('span');
            loadingText.className = 'loading';
            loadingText.textContent = 'Processing...';

            const spinner = document.createElement('div');
            spinner.className = 'spinner';

            const loadingContainer = document.createElement('div');
            loadingContainer.className = 'loading-container';
            loadingContainer.appendChild(spinner);
            loadingContainer.appendChild(loadingText);

            if (!button.parentElement.querySelector('.loading-container')) {
                button.parentElement.appendChild(loadingContainer);
            }
        }

        const style = document.createElement('style');
        style.textContent = `
.loading-container {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.spinner {
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
`;
        document.head.appendChild(style);

    </script>
</head>

<body>
    <div class="navbar">
        <h1>Admin Dashboard</h1>
        <form action="admin_logout.php" method="POST">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="admin-dashboard">
        <h2>Pending Claims</h2>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Claim request for</th>

                        <th>Uploader's Name</th>
                        <th>Uploader's description</th>
                        <th>Uploaded Image</th>

                        <th>Claimant's Name</th>
                        <th>Claimant's description</th>
                        <th>Proof Image</th>

                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>

                            <td><?php echo htmlspecialchars($row['belonging_name']); ?></td>

                            <td><?php echo htmlspecialchars($row['uploader_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['uploader_description']); ?></td>
                            <td><img src="../<?php echo $row['belonging_image']; ?>" alt="Belonging Image"></td>

                            <td><?php echo htmlspecialchars($row['claimer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['claimer_description']); ?></td>
                            <td><img src="../<?php echo $row['proof_image_path']; ?>" alt="Proof Image"></td>

                            <td class="action-buttons">
                                <form action="approve_request.php" method="POST"
                                    onsubmit="showLoading(this.querySelector('button'))">
                                    <input type="hidden" name="claim_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="approve-btn">Approve</button>
                                </form>
                                <form action="reject_request.php" method="POST"
                                    onsubmit="showLoading(this.querySelector('button'))">
                                    <input type="hidden" name="claim_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="reject-btn">Reject</button>
                                </form>
                            </td>

                        </tr>
                    <?php endwhile; ?>

                </tbody>
            </table>
        <?php else: ?>
            <p class="no-claims">No pending claims.</p>
        <?php endif; ?>
    </div>
</body>

</html>