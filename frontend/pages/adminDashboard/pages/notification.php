<?php
session_start();
require_once('../../../../server/config/db.php');
require_once('../../../../server/controller/ClaimController.php');

$db = (new Database())->connect();
$claimController = new ClaimController($db);

$claims = $claimController->getAllClaimsForAdmin(); // Or getPendingClaimsForPoster if it's the poster

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Claims</title>
</head>

<body>

    <h1>Manage Claims</h1>

    <?php foreach ($claims as $claim): ?>
        <div>
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

</body>

</html>