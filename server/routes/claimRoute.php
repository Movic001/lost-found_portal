<?php
//if session is not started, start it
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Only start the session if it's not already active
}

require_once('../config/db.php');          // Your DB connection
require_once('../controller/ClaimController.php');
require_once('../classes/postItem_class.php'); // For fetching item

$db = (new Database())->connect();
$claimController = new ClaimController($db);

// === 1. Show claim form if GET ===
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['item_id'])) {
    $itemModel = new FoundItem($db);
    $item = $itemModel->getItemById($_GET['item_id']);

    if (!$item) {
        die("Item not found.");
    }

    // Load the form with the $item data
    include('../../frontend/pages/claim.php');
    exit;
}

// === 2. Handle form submission ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['claim_request'])) {
    try {
        if (!isset($_SESSION['user_id'])) {
            //throw new Exception("You must be logged in to submit a claim.");
            $alertTitle = "Login Required";
            $alertText = "You must be logged in to submit a claim.";
            $alertIcon = "warning";
            $redirectUrl = '../../frontend/pages/login.php';
            $alertButton = "OK";

            include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
            exit;
        }

        $data = [
            'item_id' => $_POST['item_id'],
            'user_id' => $_SESSION['user_id'],
            'description' => $_POST['description'],
            'location_lost' => $_POST['location_lost'],
            'security_answer' => $_POST['security_answer']
        ];

        if ($claimController->submitClaim($data)) {
            // run an alert with a success message and redirect to notification page
            // echo "<script>alert('Claim submitted successfully!');</script>";
            // header("Location: ../../frontend/pages/claimResult.php?status=pending");
            // exit;

            $alertTitle = "Claim submitted successfully!";
            $alertText = "Your claim is pending review.";
            $alertIcon = "success";
            $redirectUrl = '../../frontend/pages/claimResult.php?status=pending';
            $alertButton = "OK";
            include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
            exit;
        } else {
            //throw new Exception("Failed to submit claim.");
            $alertTitle = "Failed to submit claim";
            $alertText = "Please try again.";
            $alertIcon = "error";
            $redirectUrl = '../../frontend/pages/claim.php?item_id=' . $_POST['item_id'];
            $alertButton = "OK";

            include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
            exit;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    //echo "Invalid request method.";
    $alertTitle = "Invalid request";
    $alertText = "Please try again.";
    $alertIcon = "error";
    $redirectUrl = '../../frontend/pages/claim.php?item_id=' . $_POST['item_id'];
    $alertButton = "OK";

    include(__DIR__ . '/../../frontend/pages/sweetAlert/alertTemplate.php');
    exit;
}
