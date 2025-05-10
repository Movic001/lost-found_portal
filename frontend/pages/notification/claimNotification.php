<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check status from query param
$status = $_GET['status'] ?? 'unknown';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Claim Notification</title>
</head>

<body>
    <h2>Claim Status</h2>
    <?php if ($status === 'pending'): ?>
        <p>Your claim request has been submitted successfully and is pending review.</p>
    <?php else: ?>
        <p>Status: <?= htmlspecialchars($status) ?></p>
    <?php endif; ?>
    <a href="../../view/view_items.php">Back to items</a>
</body>

</html>