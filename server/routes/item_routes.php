<?php
// routes/item_routes.php
require_once '../config/db.php';
require_once '../controller/itemController.php';

$database = new Database();
$db = $database->connect();
$controller = new ItemController($db);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) {
        $controller->approveItem($_POST['item_id']);
    } elseif (isset($_POST['reject'])) {
        $controller->rejectItem($_POST['item_id']);
    }
} else {
    // Handle GET requests or other methods if needed
    header("Location: ../admin/adminDashboard.php");
    exit;
}
// Redirect to the admin dashboard after processing the request
// header("Location: ../../frontend/pages/adminDashboard/pages/adminDashboard.php");
// exit;
