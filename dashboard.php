<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

$stmt = $con->prepare("SELECT dreams.*, dream_users.username FROM dreams JOIN dream_users ON dreams.user_id = dream_users.id WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Dreams</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            padding: 20px;
             background-size: cover;
        }

        a {
            text-decoration: none;
            margin-right: 20px;
            font-weight: bold;
            color: #0077b6;
        }

        a:hover {
            color: #f72585;
        }

        h2 {
            color: #f22245;
        }

        .dreams {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: start;
        }

        .dream {
            background: white;
            padding: 15px;
            width: 300px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            position: relative;
        }

        .dream h3 {
            margin: 0 0 5px 0;
            color: #333;
        }

        .dream small {
            color: #777;
        }

        .dream p {
            font-size: 14px;
            color: #444;
            margin: 10px 0;
        }

        .dream img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 6px;
        }

        .dream .actions {
            margin-top: 10px;
        }

        .dream .actions a {
            color: crimson;
            font-size: 14px;
            margin-right: 10px;
        }

        .dream .actions a:hover {
            color: darkred;
        }
        .dream img {
    width: 100%;
    height: 160px;
    object-fit: cover;
    border-radius: 6px;
    transition: transform 0.4s ease;
}

.dream img:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    z-index: 2;
}
.dream .actions a {
    padding: 4px 10px;
    border-radius: 6px;
    background-color: #ffe5ec;
    color: #d90429;
    transition: 0.3s ease;
    font-weight: bold;
}

.dream .actions a:hover {
    background-color: #d90429;
    color: #fff;
}
.dream {
    transition: 0.3s ease-in-out;
}

.dream:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
}
.dream img {
    position: relative;
    transition: 0.4s ease;
}

.dream img:hover {
    filter: brightness(75%);
}

        

    </style>
</head>
<body>

    <a href="add_dream.php">Add Dream➕</a>
    <a href="logout.php">Logout🚪</a>

    <h2>Welcome, <?= $_SESSION['username'] ?> 👋</h2>
  

    <div class="dreams">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="dream">
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <small>Posted on <?= $row['created_at'] ?></small>
                <?php if ($row['image']) { ?>
                    <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="Dream Image">
                <?php } ?>
                <p><?= htmlspecialchars($row['description']) ?></p>
                <div class="actions">
                    <a href="update_dream.php?id=<?= $row['id'] ?>">Edit✏️</a>
                    <a href="delete_dream.php?id=<?= $row['id'] ?>">Delete🗑️</a>
                </div>
            </div>
        <?php } ?>
    </div>

</body>
</html>
