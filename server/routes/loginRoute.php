<?php

// routes/loginRoute.php
// Include the database configuration file
require_once(__DIR__ . '/../config/db.php');
// Include the login controller
require_once(__DIR__ . '/../controller/loginController.php');
// intialize the database connection
$database = new Database();
$db = $database->connect();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once(__DIR__ . '/../controller/loginController.php');
} else {
    // If the request method is not POST, show an error message
    $alertTitle = "Invalid route!";
    $alertText = "Please use the correct route.";
    $alertIcon = "error";
    $redirectUrl = '../../frontend/pages/login.html';

    include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
    exit;
}
