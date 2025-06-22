<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];
$dream_id = $_GET['id'] ?? null;

if (!$dream_id) {
    die("Dream not found.");
}

$stmt = $con->prepare("SELECT * FROM dreams WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $dream_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$dream = $result->fetch_assoc();

if (!$dream) {
    die("Unauthorized or dream not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/$image");
    } else {
        $image = $dream['image'];
    }

    $update = $con->prepare("UPDATE dreams SET title = ?, description = ?, image = ? WHERE id = ? AND user_id = ?");
    $update->bind_param("sssii", $title, $description, $image, $dream_id, $user_id);
    $update->execute();

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Dream</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e0f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background: white;
            padding: 25px;
            border-radius: 8px;
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #222;
        }
        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }
        input[type="text"], textarea, input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            margin-top: 10px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background-color: #007acc;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background-color: #005fa3;
        }
    </style>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <h2>Edit Dream</h2>
        <label>Title</label>
        <input type="text" name="title" value="<?= $dream['title'] ?>" required>

        <label>Description</label>
        <textarea name="description" required><?= $dream['description'] ?></textarea>

        <label>Current Image</label>
        <?php if ($dream['image']) { ?>
            <img src="uploads/<?= $dream['image'] ?>" alt="">
        <?php } ?>

        <label>New Image (optional)</label>
        <input type="file" name="image">

        <button type="submit">Update</button>
    </form>
</body>
</html>
