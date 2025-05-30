<?php
include('../../../../server/includes/csrf_helper.php');;
require_once('../../../../server/config/db.php');
require_once('../../../../server/controller/ClaimController.php');
require_once(__DIR__ . '/../../../../server/classes/postItem_class.php');

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
    <link rel="stylesheet" href="../styles/notification.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="#" class="navbar-logo">LostFound Admin</a>
        <button class="navbar-toggle" id="navbarToggle">☰</button>
        <div class="navbar-menu" id="navbarMenu">
            <a href="./adminDashboard.php" class="navbar-link">Dashboard</a>
            <a href="./notification.php" class="navbar-link active">Claims</a>
            <!--a href="#" class="navbar-link">Items</a>
            <a href="#" class="navbar-link">Settings</a>
            <a href="#" class="navbar-link">Logout</a-->
        </div>
    </nav>

    <!-- Main content -->
    <div class="container">
        <header class="page-header">
            <h1 class="page-title">Manage Claims</h1>
            <div class="filter-controls">
                <!-- Filter controls can be added here if needed -->
            </div>
        </header>

        <div class="claims-list">
            <?php foreach ($claims as $claim): ?>
                <div class="claim-card" data-claim-id="<?= $claim['id'] ?>">
                    <div class="card-header">
                        <?= htmlspecialchars($claim['item_name']) ?>
                        <span class="status-badge <?= strtolower($claim['status']) ?>"><?= ucfirst($claim['status']) ?></span>
                    </div>

                    <?php if (!empty($claim['image_path'])): ?>
                        <img src="../../../uploads/<?= htmlspecialchars(basename($claim['image_path'])) ?>" alt="Item Image" class="item-image" />
                    <?php else: ?>
                        <div class="item-image" aria-label="No image available"></div>
                    <?php endif; ?>

                    <div class="card-body">
                        <div class="item-details">
                            <div class="item-detail">
                                <span class="detail-label">Claimant</span>
                                <span class="detail-value"><?= htmlspecialchars($claim['claimant_name']) ?></span>
                            </div>
                            <div class="item-detail">
                                <span class="detail-label">Description</span>
                                <span class="detail-value"><?= htmlspecialchars($claim['description']) ?></span>
                            </div>
                            <div class="item-detail">
                                <span class="detail-label">Location Lost</span>
                                <span class="detail-value"><?= htmlspecialchars($claim['location_lost']) ?></span>
                            </div>
                        </div>

                        <div class="security-section">
                            <div class="item-detail">
                                <span class="detail-label">Security Question</span>
                                <span class="detail-value"><?= htmlspecialchars($claim['unique_question']) ?></span>
                            </div>
                            <div class="item-detail">
                                <span class="detail-label">Security Answer</span>
                                <span class="detail-value"><?= htmlspecialchars($claim['security_answer']) ?></span>
                            </div>
                        </div>
                    </div>

                    <form action="../../../../server/routes/approveRejectRoute.php" method="POST" class="claim-form">
                        <input type="hidden" name="claim_id" value="<?= $claim['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <div class="card-actions">
                            <button type="submit" name="action" value="approve" class="btn btn-approve">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 6L9 17l-5-5"></path>
                                </svg>
                                Approve
                            </button>
                            <button type="submit" name="action" value="reject" class="btn btn-reject">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 6L6 18M6 6l12 12"></path>
                                </svg>
                                Reject
                            </button>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>

            <?php if (empty($claims)): ?>
                <div class="empty-state">
                    <i>📦</i>
                    <h3>No claims to review</h3>
                    <p>When users submit claims, they will appear here for your review.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Toast notification -->
    <div class="toast" id="toast"></div>

    <script src="../script/notification.js"></script>
</body>

</html>