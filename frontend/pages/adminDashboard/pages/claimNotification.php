<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../../../../server/config/db.php'); // Path to your database connection file

$status = $_GET['status'] ?? 'unknown';
$claimId = $_GET['claim_id'] ?? null;
$posterInfo = null;

if ($status === 'approved' && $claimId) {
    try {
        $db = (new Database())->connect();

        $query = "SELECT users.fullName, users.email, users.mobile
                  FROM claims
                  JOIN found_items ON claims.item_id = found_items.id
                  JOIN users ON found_items.user_id = users.id
                  WHERE claims.id = :claim_id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':claim_id', $claimId);
        $stmt->execute();
        $posterInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("DB Error: " . $e->getMessage());
    }
}
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

    <?php elseif ($status === 'approved' && $posterInfo): ?>
        <p>Your claim was <strong>approved</strong>! Here is the poster's contact information:</p>
        <ul>
            <li><strong>Name:</strong> <?= htmlspecialchars($posterInfo['fullName']) ?></li>
            <li><strong>Email:</strong> <?= htmlspecialchars($posterInfo['email']) ?></li>
            <li><strong>Phone:</strong> <?= htmlspecialchars($posterInfo['mobile']) ?></li>
        </ul>

    <?php else: ?>
        <p>Status: <?= htmlspecialchars($status) ?></p>
    <?php endif; ?>

    <a href="./manageClaim.php">Back to claim</a>
</body>

</html>