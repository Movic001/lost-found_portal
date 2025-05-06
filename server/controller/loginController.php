<?php
session_start();
require_once(__DIR__ . '/../config/db.php');
require_once(__DIR__ . '/../model/user.php');
// Using the existing User model

$user = new User($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($user->login($email, $password)) {
        if ($_SESSION['user_role'] === 'admin') {
            // Redirect to admin dashboard or success page
            echo "<script>alert('Login succesfully.');window.location.href='../../frontend/pages/adminDashboard/pages/adminDashboard.php';</script>";
            // include('../../frontend/status/admin_login_success.html');
        } else {
            // Redirect to user home page or success page
            echo "<script>alert('Login succesfully.');window.location.href='../../frontend/pages/dashboard.php';</script>";
            // include('../../frontend/status/user_login_success.html');
        }
    } else {
        echo "<script>alert('❌ Invalid email or password.');window.location.href='../../frontend/pages/login.html';</script>";
    }
}
