<?php
//if section is not started, start it
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

require_once '../config/db.php';          // Your DB connection
require_once '../classes/postItem_class.php';  // The item class


$db = (new Database())->connect();
$itemModel = new FoundItem($db);

// Make sure the item_id is provided via GET
if (!isset($_GET['item_id']) || empty($_GET['item_id'])) {
    die("Missing or invalid item ID.");
}

$item_id = $_GET['item_id'];
$item = $itemModel->getItemById($item_id);

if (!$item) {
    die("Item not found.");
}


include '../../frontend/pages/claim.php';    // Show the form and send $item to it