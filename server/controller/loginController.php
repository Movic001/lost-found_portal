<?php
session_start();
require_once(__DIR__ . '/../config/db.php');
require_once(__DIR__ . '/../classes/user.php');

$database = new Database();
$db = $database->connect();
$user = new User($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            throw new Exception("Email and password are required");
        }

        if ($user->login($email, $password)) {
            $redirectUrl = ($_SESSION['user_role'] === 'admin')
                ? '../../frontend/pages/adminDashboard/pages/adminDashboard.php'
                : '../../frontend/pages/dashboard.php';

            // Output JavaScript for alert and redirect
            echo "<script>
                alert('Login successful');
                window.location.href = '$redirectUrl';
            </script>";
            exit;
        } else {
            throw new Exception("Invalid email or password");
        }
    } catch (Exception $e) {
        echo "<script>
            alert('Error: " . addslashes($e->getMessage()) . "');
            window.location.href = '../../frontend/pages/login.html';
        </script>";
        exit;
    }
} else {
    echo "<script>
        alert('Invalid request method');
        window.location.href = '../../frontend/pages/login.html';
    </script>";
    exit;
}
