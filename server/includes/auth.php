<?php
require_once(__DIR__ . '/../config/db.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Only start the session if it's not already active
}
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first!'); window.location.href='../../frontend/pages/login.html';</script>";
    exit;
}
