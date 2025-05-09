<?php
session_start();

require_once('../config/db.php');
require_once('../controller/ClaimController.php');

// Only allow access if user is admin or item poster
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

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
        header("Location: ../../frontend/pages/claimRequests.php?success=1");
        exit;
    } else {
        echo "Failed to update claim status.";
    }
} else {
    echo "Invalid request.";
}
