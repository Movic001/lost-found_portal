<?php
//if session is not started, start it
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Only start the session if it's not already active
}
require_once(__DIR__ . '/../controller/deleteController.php');

if (!isset($_SESSION['user_id'])) {
    // echo "<script>alert('You must be logged in.'); window.location.href='../../frontend/pages/login.php';</script>";
    // exit;
    $alertTitle = "Login Required";
    $alertText = "You must be logged in.";
    $alertIcon = "warning";
    $redirectUrl = '../../frontend/pages/login.php';
    $alertButton = "OK";

    include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
    exit;
}

if (isset($_GET['id'])) {
    $itemId = intval($_GET['id']);
    $userId = $_SESSION['user_id'];

    if ($deleteItemController->deleteItem($itemId, $userId)) {
        //echo "<script>alert('Item deleted successfully'); window.location.href='../../frontend/pages/view_items.php';</script>";
        $alertTitle = "Success";
        $alertText = "Item deleted successfully.";
        $alertIcon = "success";
        $redirectUrl = '../../frontend/pages/view_items.php';
        $alertButton = "OK";

        include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
        exit;
    } else {
        //    echo "<script>alert('Unauthorized or item not found.'); window.location.href='../../frontend/pages/view_items.php';</script>";
        $alertTitle = "Error";
        $alertText = "Unauthorized or item not found.";
        $alertIcon = "error";
        $redirectUrl = '../../frontend/pages/view_items.php';
        $alertButton = "OK";

        include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
        exit;
    }
} else {
    // echo "<script>alert('No item ID provided.'); window.location.href='../../frontend/pages/view_items.php';</script>";
    $alertTitle = "Error";
    $alertText = "No item ID provided.";
    $alertIcon = "error";
    $redirectUrl = '../../frontend/pages/view_items.php';
    $alertButton = "OK";
    include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
    exit;
}
