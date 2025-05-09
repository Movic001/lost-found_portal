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
    echo "<script>alert('Invalid route!'); window.location.href='../../frontend/pages/login.html';</script>";
}
