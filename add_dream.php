<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $user_id = $_SESSION['id'];

    $image = $_FILES["image"]["name"];
    $tmp = $_FILES["image"]["tmp_name"];
    if (!empty($image)) {
        move_uploaded_file($tmp, "uploads/$image");
    }

    $stmt = $con->prepare("INSERT INTO dreams (title, description, image, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $description, $image, $user_id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Dream</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #f9f7d9, #fbc2eb);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }
        input[type="text"], textarea, input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        textarea {
            resize: vertical;
            height: 100px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background-color: #f093fb;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        button:hover {
            background-color: #ec38bc;
        }
    </style>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <h2>Add Your Dream</h2>
        <label>Title</label>
        <input type="text" name="title" required>

        <label>Description</label>
        <textarea name="description" required></textarea>

        <label>Image (optional)</label>
        <input type="file" name="image">

        <button type="submit">Post Dream</button>
    </form>
</body>
</html>
