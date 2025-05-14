<?php
//if session is not started, start it
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Only start the session if it's not already active
}

require_once('../config/db.php');
require_once('../controller/ClaimController.php');
// Only allow access if user is admin or item poster
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("Unauthorized access.");
}
// Check if the user is logged in and has the correct role
//if user is not logged in, redirect to login page

$db = (new Database())->connect();
$claimController = new ClaimController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['claim_id']) && isset($_POST['action'])) {
    $claimId = $_POST['claim_id'];
    $action = $_POST['action'];

    if (!in_array($action, ['approve', 'reject'])) {
        die("Invalid action.");
    }

    $status = $action === 'approve' ? 'approved' : 'rejected';

    if ($claimController->updateClaimStatus($claimId, $status)) {
        header("Location: ../../frontend\pages\adminDashboard\pages\claimNotification.php?status=$status&claim_id=$claimId");

        // header("Location: ../../frontend/pages/adminDashboard/pages/notification.php");
        // Optionally, you can set a session message to display a success message
        exit;
    } else {
        echo "Failed to update claim status.";
    }
} else {
    echo "Invalid request.";
}
