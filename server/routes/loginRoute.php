<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once(__DIR__ . '/../controller/loginController.php');
} else {
    echo "<script>alert('Invalid route!'); window.location.href='../../frontend/pages/login.html';</script>";
}
