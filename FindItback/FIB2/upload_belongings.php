<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Found Belonging</title>
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
            padding: 1.2rem;
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

        .navbar .nav-links a:hover {
            color: #fad0c4;
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
            background-color: #2c3e50;
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
<script>
        document.addEventListener("DOMContentLoaded", () => {
            const fileInput = document.querySelector(".file-upload input[type='file']");
            const fileUploadSpan = document.querySelector(".file-upload span");

            fileInput.addEventListener("change", () => {
                if (fileInput.files.length > 0) {
                    fileUploadSpan.textContent = fileInput.files[0].name;
                } else {
                    fileUploadSpan.textContent = "Drag & Drop your file here or click to upload";
                }
            });

            const fileUpload = document.querySelector(".file-upload");
            fileUpload.addEventListener("dragover", (event) => {
                event.preventDefault();
                fileUpload.style.borderColor = "#ff6b6b";
            });

            fileUpload.addEventListener("dragleave", () => {
                fileUpload.style.borderColor = "#ddd";
            });

            fileUpload.addEventListener("drop", (event) => {
                event.preventDefault();
                fileInput.files = event.dataTransfer.files;
                if (fileInput.files.length > 0) {
                    fileUploadSpan.textContent = fileInput.files[0].name;
                } else {
                    fileUploadSpan.textContent = "Drag & Drop your file here or click to upload";
                }
                fileUpload.style.borderColor = "#ddd";
            });
        });
    </script>
    <div class="navbar">
        <div class="logo">FindItBack</div>
    </div>

    <div class="form-container">
        <h1>Upload Found Belonging</h1>
        <form action="process_upload.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Belonging Name" required>
            <input type="text" name="type" placeholder="Type (e.g., Book, Bag, Gadget)" required>
            <input type="text" name="location_found" placeholder="Where You Found It" required>
            <input type="date" name="date_found" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <div class="file-upload">
                <input type="file" name="image" accept="image/*" required>
                <span>Drag & Drop your file here or click to upload</span>
            </div>
            <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>
