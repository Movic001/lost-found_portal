<?php
//if session is not started, start it
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Only start the session if it's not already active
}
require_once '../../server/config/db.php';
require_once '../../server/controller/ClaimController.php';

$db = (new Database())->connect();
$claimController = new ClaimController($db);

// Check if admin or normal user
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

$claims = $isAdmin
    ? $claimController->getAllClaimsForAdmin()
    : $claimController->getPendingClaimsForPoster($_SESSION['user_id']);
?>

<h2><?= $isAdmin ? 'All Claim Requests' : 'Claim Requests for Your Items' ?></h2>

<table border="1">
    <thead>
        <tr>
            <th>Item</th>
            <th>Claimed By</th>
            <th>Description</th>
            <th>Location Lost</th>
            <th>Answer</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($claims as $claim): ?>
            <tr>
                <td><?= htmlspecialchars($claim['item_name']) ?></td>
                <td><?= htmlspecialchars($claim['claimant_name']) ?></td>
                <td><?= htmlspecialchars($claim['description']) ?></td>
                <td><?= htmlspecialchars($claim['location_lost']) ?></td>
                <td><?= htmlspecialchars($claim['security_answer']) ?></td>
                <td><?= htmlspecialchars($claim['status']) ?></td>
                <td>
                    <form action="../../server/routes/approveRejectRoute.php" method="POST">
                        <input type="hidden" name="claim_id" value="<?= $claim['id'] ?>">
                        <button type="submit" name="action" value="approve">Approve</button>
                        <button type="submit" name="action" value="reject">Reject</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>