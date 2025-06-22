<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$dream_id = $_GET['id'] ?? null;
$user_id = $_SESSION['id'];

if ($dream_id) {
    $stmt = $con->prepare("DELETE FROM dreams WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $dream_id, $user_id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit;
?>
