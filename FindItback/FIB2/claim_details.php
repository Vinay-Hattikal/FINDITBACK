<?php
session_start();
include('db_config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if belonging_id is passed in the URL
if (isset($_GET['belonging_id'])) {
    $belonging_id = $_GET['belonging_id'];

    // Fetch belonging details from the database
    $sql = "SELECT * FROM belongings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $belonging_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $belonging = $result->fetch_assoc();

    if (!$belonging) {
        echo "Belonging not found.";
        exit();
    }
} else {
    echo "No belonging selected.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Belonging</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg,rgb(84, 150, 231) 0%,rgb(193, 214, 255) 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        .navbar {
            width: 100%;
            background-color: #2c3e50;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar .logo {
            font-size: 1.5rem;
            color: #fff;
            font-weight: bold;
            margin-left: 0.8rem;
        }

        .navbar .nav-links {
            display: flex;
            gap: 1rem;
        }

        .navbar .nav-links a {
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }


        .form-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 90%;
            text-align: center;
            margin-top: 2rem;
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: #444;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        input, textarea, button {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        input:focus, textarea:focus {
            border-color: #2c3e50;
            outline: none;
        }

        textarea {
            resize: none;
            height: 100px;
        }

        .file-upload {
            position: relative;
            width: 100%;
            height: 150px;
            border: 2px dashed #ddd;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .file-upload:hover {
            border-color: #2c3e50;
        }

        .file-upload input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload span {
            font-size: 1rem;
            color: #aaa;
        }

        button {
            background-color: #2c3e50;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: ;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.5rem;
            }

            .form-container {
                padding: 1.5rem;
            }

            input, textarea, button {
                font-size: 0.9rem;
            }

            .navbar .nav-links a {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.2rem;
            }

            .navbar .logo {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">FindItBack</div>
        </div>
    </div>

    <div class="form-container">
        <h1>Claim Belonging</h1>
        <form action="process_claim.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="belonging_id" value="<?php echo $belonging['id']; ?>">

            <label for="lost_location">Where did you lose it?</label>
            <input type="text" name="lost_location" required>

            <label for="lost_date">When did you lose it?</label>
            <input type="date" name="lost_date" required>

            <label for="proof_description">Proof Description</label>
            <textarea name="proof_description" required></textarea>

            <label for="proof_image">Proof Image</label>
            <div class="file-upload">
                <input type="file" name="proof_image" accept="image/*" required>
                <span>Drag & Drop your file here or click to upload</span>
            </div>

            <button type="submit">Submit Claim</button>
        </form>
    </div>
</body>
</html>

