<?php
require_once(__DIR__ . '../config/db.php');
session_start();

$userId = $_GET['user_id'] ?? null;

if ($userId) {
    $db = (new Database())->connect();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: ../../frontend/pages/adminDashboard/pages/adminDashboard.php");
        } else {
            header("Location: ../../frontend/pages/dashboard.php");
        }
        exit;
    } else {
        echo "User not found.";
    }
} else {
    echo "No user ID provided.";
}
