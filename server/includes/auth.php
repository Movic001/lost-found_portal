<?php
require_once(__DIR__ . '/../config/db.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Only start the session if it's not already active
}
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first!'); window.location.href='../../frontend/pages/login.html';</script>";
    exit;
}
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "User not logged in.";
    exit;
}
