<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('../config/db.php');
require_once('../includes/csrf_helper.php');
require_once('../classes/user.php');
require_once('../controller/ClaimController.php');

// Debug: Log received POST data
error_log("Received POST data: " . print_r($_POST, true));

// Check authorization
$db = (new Database())->connect();
$user = new User($db);
if (!isset($_SESSION['user_id']) || !$user->isAdmin($_SESSION['user_id'])) {
    die("<script>
        alert('Unauthorized access');
        window.location.href = '../../frontend/pages/login.html';
    </script>");
}

// Validate request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("<script>
        alert('Invalid request method');
        window.location.href = '../../frontend/pages/adminDashboard/pages/adminDashboard.php';
    </script>");
}

// Check required parameters
$required = ['claim_id', 'action', 'csrf_token'];
foreach ($required as $field) {
    if (!isset($_POST[$field])) {
        error_log("Missing field: $field");
        die("<script>
            alert('Missing required parameters');
            window.location.href = '../../frontend/pages/adminDashboard/pages/adminDashboard.php';
        </script>");
    }
}

try {
    // Sanitize inputs
    $claimId = (int)$_POST['claim_id'];
    $action = $_POST['action'] === 'approve' ? 'approved' : 'rejected';
    $csrfToken = $_POST['csrf_token'];

    // Verify CSRF token
    if (!verifyCsrfToken($csrfToken)) {
        throw new Exception("Security token expired. Please refresh the page.");
    }

    // Process claim
    $claimController = new ClaimController($db);
    if (!$claimController->updateClaimStatus($claimId, $action)) {
        throw new Exception("Failed to update claim status");
    }

    // Handle role upgrade if approving
    if ($action === 'approved') {
        $claim = $claimController->getClaimById($claimId);
        $userId = $claim['user_id'];

        if (!$user->updateUserRole($userId, 'admin')) {
            throw new Exception("Failed to update user role");
        }

        // Special handling if admin approved their own claim
        if ($_SESSION['user_id'] == $userId) {
            session_unset();
            session_destroy();
            die("<script>
                alert('Your account has been upgraded to admin. Please login again.');
                window.location.href = '../../frontend/pages/login.html';
            </script>");
        }
    }

    // Success response
    echo "<script>
        alert('Claim {$action} successfully');
        window.location.href = '../../frontend/pages/adminDashboard/pages/claimNotification.php?status={$action}&claim_id={$claimId}';
    </script>";
    exit;
} catch (Exception $e) {
    error_log("Error in approveRejectRoutes: " . $e->getMessage());
    echo "<script>
        alert('Error: " . addslashes($e->getMessage()) . "');
        window.location.href = '../../frontend/pages/adminDashboard/pages/adminDashboard.php';
    </script>";
    exit;
}
