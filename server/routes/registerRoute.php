<?php
// Include necessary files
require_once(__DIR__ . '/../controller/registerController.php');

// Handle POST request for registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new RegisterController();
    $controller->register();
} else {
    echo "<script>alert('Invalid Route'); window.location.href='../../frontend/html/register.html';</script>";
}
