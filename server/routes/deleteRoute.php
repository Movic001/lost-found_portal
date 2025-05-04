<?php
session_start();
require_once(__DIR__ . '/../controller/deleteController.php');

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in.'); window.location.href='../../frontend/pages/login.php';</script>";
    exit;
}

if (isset($_GET['id'])) {
    $itemId = intval($_GET['id']);
    $userId = $_SESSION['user_id'];

    if ($deleteItemController->deleteItem($itemId, $userId)) {
        echo "<script>alert('Item deleted successfully'); window.location.href='../../frontend/pages/view_items.php';</script>";
    } else {
        echo "<script>alert('Unauthorized or item not found.'); window.location.href='../../frontend/pages/view_items.php';</script>";
    }
} else {
    echo "<script>alert('No item ID provided.'); window.location.href='../../frontend/pages/view_items.php';</script>";
}
