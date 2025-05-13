<?php
//if session is not started, start it
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Only start the session if it's not already active
}

if (isset($_POST['logOut'])) {
    session_destroy();
    echo "<script>confirm('You have logged out successfully!'); window.location.href='../../frontend/pages/login.html';</script>";
    exit;
}
// Check if the form is submitted
if (isset($_POST['login'])) {
    // Validate and sanitize form data
    $email = trim($_POST['email']);
    $password = $_POST['password'];
} else {
    echo "<script>alert('Invalid request.'); window.location.href='../../frontend/pages/login.html';</script>";
    exit;
    include('../../frontend/pages/dashboard.php');
}
