<?php
// Include the database connection file
require_once(__DIR__ . '/../config/db.php');

// Include the PostItemController
require_once(__DIR__ . '/../controller/postItemController.php');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postItem'])) {
    // Collect and sanitize form inputs
    $formData = [
        'item_name' => trim($_POST['item_name']),
        'category' => trim($_POST['category']),
        'description' => trim($_POST['description']),
        'location_found' => trim($_POST['location_found']),
        'date_found' => trim($_POST['date_found']),
        'person_name' => trim($_POST['person_name']),
        'contact_info' => trim($_POST['contact_info']),
        'unique_question' => trim($_POST['unique_question']), // Collect unique question
    ];

    // Instantiate the PostItemController
    $postItemController = new PostItemController($db);

    // Call the postFoundItem method to handle posting the item
    $postItemController->postFoundItem($formData, $_FILES['image_path']);
}
