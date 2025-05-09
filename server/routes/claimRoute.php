<?php
session_start();

require_once '../config/db.php';          // Your DB connection
require_once '../classes/postItem_class.php';  // The item class


$db = (new Database())->connect();        // Connect to DB
$itemModel = new FoundItem($db);          // Create item object

if (!isset($_GET['item_id'])) {
    die("Missing item ID.");
}

$item = $itemModel->getItemById($_GET['item_id']);  // Get the item from DB

if (!$item) {
    die("Item not found.");
}

include '../frontend/pages/claim.php';    // Show the form and send $item to it
