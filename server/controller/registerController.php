<?php
session_start();
require_once(__DIR__ . '/../config/db.php');
require_once(__DIR__ . '/../classes/user.php');

// Initialize the database connection
$database = new Database();
$db = $database->connect();

class RegisterController
{
    public function register()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
            // Sanitize and trim input data
            $formData = [
                'fullName' => trim($_POST['fullName']),
                'userName' => trim($_POST['userName']),
                'mobile'   => trim($_POST['mobile']),
                'email'    => trim($_POST['email']),
                'address'  => trim($_POST['address']),
                'city'     => trim($_POST['city']),
                'password' => $_POST['password']
            ];

            // Create a User object
            $user = new User($GLOBALS['db']);

            // Attempt to register user
            try {
                if ($user->register($formData)) {
                    // Redirect to login page on success
                    header("Location: ../../frontend/pages/login.html");
                    exit();
                } else {
                    echo "<script>alert('❌ Registration failed. Email might be taken.');</script>";
                }
            } catch (PDOException $e) {
                echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
            }
        } else {
            echo "<script>alert('Invalid request'); window.location.href='../../frontend/html/register.html';</script>";
        }
    }
}
