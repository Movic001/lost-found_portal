<?php
session_start();
require_once(__DIR__ . '/../config/db.php');
require_once(__DIR__ . '/../classes/user.php');

$database = new Database();
$db = $database->connect();
$user = new User($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $emailRaw = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $email = filter_var(trim($emailRaw), FILTER_SANITIZE_EMAIL);

    // Validation checks
    if (empty($emailRaw) || empty($password)) {
        $alertTitle = "Validation Error";
        $alertText = "Email and password are required.";
        $alertIcon = "warning";
        $redirectUrl = '../../frontend/pages/login.html';
        include(__DIR__ . '/alertTemplate.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alertTitle = "Validation Error";
        $alertText = "Please enter a valid email address.";
        $alertIcon = "warning";
        $redirectUrl = '../../frontend/pages/login.html';
        include(__DIR__ . '/alertTemplate.php');
        exit;
    }

    try {
        if ($user->login($email, $password)) {
            // Make sure user_role is set in session during login() method
            $redirectUrl = ($_SESSION['user_role'] === 'admin')
                ? '../../frontend/pages/adminDashboard/pages/adminDashboard.php'
                : '../../frontend/pages/dashboard.php';

            $alertTitle = "Login successful";
            $alertText = "Welcome back!";
            $alertIcon = "success";
            $alertRedirect = $redirectUrl;

            include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
            exit;
        } else {
            $alertTitle = "Login Failed";
            $alertText = "Invalid email or password.";
            $alertIcon = "error";
            $redirectUrl = '../../frontend/pages/login.html';

            include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
            exit;
        }
    } catch (Exception $e) {
        $alertTitle = "Error";
        $alertText = $e->getMessage();
        $alertIcon = "error";
        $redirectUrl = '../../frontend/pages/login.html';
        include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
        exit;
    }
} else {
    // Invalid request method fallback
    // echo "<script>
    //     alert('Invalid request method');
    //     window.location.href = '../../frontend/pages/login.html';
    // </script>";
    // exit;
    $alertTitle = "Invalid request";
    $alertText = "Invalid request method";
    $alertIcon = "error";
    $redirectUrl = '../../frontend/pages/login.html';

    include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
    exit;
}
