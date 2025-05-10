<?php
session_start();
require_once('../../server/config/db.php');
require_once('../../server/controller/ClaimController.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.html");
    exit();
}

$db = (new Database())->connect();
$claimController = new ClaimController($db);

// Get claims only for the current user who posted the found items
$userId = $_SESSION['user_id'];
$claims = $claimController->getPendingClaimsForPoster($userId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Item Claims</title>
</head>

<body>

    <h1>Claims on Your Posted Items</h1>

    <?php if (empty($claims)): ?>
        <p>No pending claims for your items.</p>
    <?php else: ?>
        <?php foreach ($claims as $claim): ?>
            <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 15px;">
                <p><strong>Item Name:</strong> <?= htmlspecialchars($claim['item_name']) ?></p>
                <p><strong>Claimant:</strong> <?= htmlspecialchars($claim['claimant_name']) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($claim['description']) ?></p>
                <p><strong>Location Lost:</strong> <?= htmlspecialchars($claim['location_lost']) ?></p>
                <p><strong>Security Answer:</strong> <?= htmlspecialchars($claim['security_answer']) ?></p>
                <p><strong>Status:</strong> <?= ucfirst($claim['status']) ?></p>

                <form action="../../server/routes/claimApprovalRoute.php" method="POST">
                    <input type="hidden" name="claim_id" value="<?= $claim['id'] ?>">
                    <button type="submit" name="action" value="approved">Approve</button>
                    <button type="submit" name="action" value="rejected">Reject</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</body>

</html>