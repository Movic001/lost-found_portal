<?php
//if session is not started, start it
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Only start the session if it's not already active
}
require_once(__DIR__ . '../../controller/edit_itemsController.php');
require_once(__DIR__ . '/../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $editItemController = new EditItemController($db);

    $data = [
        'id' => $_POST['id'],
        'item_name' => $_POST['item_name'],
        'category' => $_POST['category'],
        'description' => $_POST['description'],
        'location_found' => $_POST['location_found'],
        'date_found' => $_POST['date_found'],
        'person_name' => $_POST['person_name'],
        'contact_info' => $_POST['contact_info'],
        'existing_image_path' => $_POST['existing_image_path'] ?? '',
        'unique_question' => $_POST['unique_question']
    ];

    $file = $_FILES['image_path'] ?? null;

    if ($editItemController->updateItem($data, $file)) {
        // echo "<script>alert('Item updated successfully'); window.location.href='../../frontend/pages/view_items.php';</script>";
        $alertTitle = "Item updated successfully!";
        $alertText = "Your item has been updated.";
        $alertIcon = "success";
        $redirectUrl = '../../frontend/pages/view_items.php';
        $alertButton = "OK";

        include('../../frontend/pages/sweetAlert/alertTemplate.php');
        exit;
    } else {
        //echo "<script>alert('Failed to update item.'); window.history.back();</script>";
        $alertTitle = "Update Failed!";
        $alertText = "There was an error updating the item.";
        $alertIcon = "error";
        $redirectUrl = '../../frontend/pages/view_items.php';
        $alertButton = "OK";

        include('../../frontend/pages/sweetAlert/alertTemplate.php');
        exit;
    }
} else {
    //echo "<script>alert('Invalid request.'); window.history.back();</script>";
    $alertTitle = "Invalid request!";
    $alertText = "Please check the request method.";
    $alertIcon = "error";
    $redirectUrl = '../../frontend/pages/view_items.php';
    $alertButton = "OK";

    include('../../frontend/pages/sweetAlert/alertTemplate.php');
    exit;
}
