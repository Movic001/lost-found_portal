<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Load required files
require_once('../config/db.php');
require_once('../includes/csrf_helper.php');
require_once('../classes/user.php');
require_once('../controller/ClaimController.php');

// Database and class instances
$db = (new Database())->connect();
$user = new User($db);

// ✅ Validate session and admin role
if (!$user->validateSession() || $_SESSION['user_role'] !== 'admin') {
    die("<script>
        alert('Unauthorized access');
        window.location.href = '../../frontend/pages/login.html';
    </script>");
}

// ✅ Ensure request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("<script>
        alert('Invalid request method');
        window.location.href = '../../frontend/pages/adminDashboard/pages/adminDashboard.php';
    </script>");
}

// ✅ Check required POST fields
$required = ['claim_id', 'action', 'csrf_token'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        die("<script>
            alert('Missing required parameters: $field');
            window.location.href = '../../frontend/pages/adminDashboard/pages/adminDashboard.php';
        </script>");
    }
}

// ✅ Main logic inside try block
try {
    // Sanitize inputs
    $claimId = (int)$_POST['claim_id'];
    $action = ($_POST['action'] === 'approve') ? 'approved' : 'rejected';
    $csrfToken = $_POST['csrf_token'];

    // ✅ CSRF protection
    if (!verifyCsrfToken($csrfToken)) {
        throw new Exception("Invalid or expired security token. Please refresh the page and try again.");
    }

    // ✅ Process claim action
    $claimController = new ClaimController($db);
    $success = $claimController->updateClaimStatus($claimId, $action);
    if (!$success) {
        throw new Exception("Failed to update claim status.");
    }

    // ✅ Optional email notification logic
    $claim = $claimController->getClaimById($claimId);
    if ($claim) {
        $email = $claim['email'] ?? '';
        $itemName = $claim['item_name'] ?? 'your item';
        $statusMessage = ($action === 'approved')
            ? "Your claim for <b>$itemName</b> has been <b>approved</b>. Please contact the person who posted it."
            : "Your claim for <b>$itemName</b> has been <b>rejected</b>. Please double-check your security answer or try again later.";

        if (!empty($email)) {
            $subject = "Claim $action - Lost & Found Portal";
            $message = "
                <html>
                <head><title>$subject</title></head>
                <body>
                    <p>Hello,</p>
                    <p>$statusMessage</p>
                    <p>Thank you,<br>The Admin Team</p>
                </body>
                </html>
            ";
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8\r\n";
            $headers .= "From: no-reply@campusportal.com\r\n";

            // Send email
            @mail($email, $subject, $message, $headers); // Use mail() or PHPMailer
        }
    }

    // ✅ Redirect with success
    echo "<script>
        alert('Claim {$action} successfully');
        window.location.href = '../../frontend/pages/adminDashboard/pages/claimNotification.php?status={$action}&claim_id={$claimId}';
    </script>";
    exit;
} catch (Exception $e) {
    error_log("Error in approveRejectRoute: " . $e->getMessage());
    echo "<script>
        alert('Error: " . addslashes($e->getMessage()) . "');
        window.location.href = '../../frontend/pages/adminDashboard/pages/adminDashboard.php';
    </script>";
    exit;
}
