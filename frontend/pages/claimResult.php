<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../../server/config/db.php'); // Adjust path as needed

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$userId = $_SESSION['user_id'];

try {
    $db = (new Database())->connect();

    $query = "SELECT 
                claims.status, 
                claims.created_at,
                found_items.item_name,
                users.fullName AS poster_name,
                users.email AS poster_email,
                users.mobile AS poster_phone
              FROM claims
              JOIN found_items ON claims.item_id = found_items.id
              JOIN users ON found_items.user_id = users.id
              WHERE claims.user_id = :user_id
              ORDER BY claims.created_at DESC";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $claims = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Claim Notifications</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/claimResult.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="#" class="navbar-logo">LostFound</a>
        <button class="navbar-toggle" id="navbarToggle">☰</button>
        <div class="navbar-menu" id="navbarMenu">
            <a href="./dashboard.php" class="navbar-link">Dashboard</a>
            <!--a href="#" class="navbar-link">Items</a>
            <a href="#" class="navbar-link">Settings</a>
            <a href="#" class="navbar-link">Logout</a-->
        </div>
    </nav>
    <h2>My Claim Notifications</h2>

    <?php if (empty($claims)): ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>No claims found.</p>
        </div>
    <?php else: ?>
        <ul>
            <?php foreach ($claims as $claim): ?>
                <li data-status="<?= htmlspecialchars($claim['status']) ?>">
                    <strong>Item:</strong> <?= htmlspecialchars($claim['item_name']) ?><br>
                    <strong>Status:</strong> <?= htmlspecialchars($claim['status']) ?><br>

                    <?php if ($claim['status'] === 'pending'): ?>
                        <em>Waiting for poster/admin approval...</em>
                    <?php elseif ($claim['status'] === 'approved'): ?>
                        <strong>Poster Info:</strong><br>
                        Name: <?= htmlspecialchars($claim['poster_name']) ?><br>
                        Email: <?= htmlspecialchars($claim['poster_email']) ?><br>
                        Phone: <?= htmlspecialchars($claim['poster_phone']) ?><br>
                    <?php elseif ($claim['status'] === 'rejected'): ?>
                        <em>Your claim was rejected.</em>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <script src="../script/claimResult.js"></script>
</body>

</html>