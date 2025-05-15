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
                    // header("Location: ../../frontend/pages/login.html");
                    // exit();
                    $alertTitle = "Registration succcefully completed";
                    $alertText = "You can now log in with your credentials.";
                    $alertIcon = "success";
                    $redirectUrl = '../../frontend/pages/login.html';
                    $alertButton = "OK";

                    include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
                    exit;
                } else {
                    //echo "<script>alert('❌ Registration failed. Email might be taken.');</script>";
                    $alertTitle = "Registration failed";
                    $alertText = "Email might be taken.";
                    $alertIcon = "error";
                    $redirectUrl = '../../frontend/pages/register.html';
                    $alertButton = "OK";

                    include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
                    exit;
                }
            } catch (PDOException $e) {
                //echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
                $alertTitle = "Error";
                $alertText = "An error occurred during registration.";
                $alertIcon = "error";
                $redirectUrl = '../../frontend/pages/register.html';
                $alertButton = "OK";

                include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
                exit;
            }
        } else {
            // echo "<script>alert('Invalid request'); window.location.href='../../frontend/html/register.html';</script>";
            // exit;
            $alertTitle = "Invalid request";
            $alertText = "Invalid request";
            $alertIcon = "error";
            $redirectUrl = '../../frontend/pages/register.html';
            $alertButton = "OK";
            include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
            exit;
        }
    }
}
