<?php
//if session is not started, start it
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Only start the session if it's not already active
}
require_once('../../server/config/db.php');
require_once('../../server\classes\postItem_class.php');

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if not logged in
    header("Location: ./login.html");
    exit;
}

$userId = $_SESSION['user_id'];

$db = (new Database())->connect();
$foundItemController = new FoundItem($db);

// Fetch only the current user's items
$userItems = $foundItemController->getItemsByUserId($userId);
?>
<!-- HTML and CSS for the page -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Posted Items</title>
    <link rel="stylesheet" href="../styles/userPost.css">
</head>

<body>

    <!-- Responsive Navbar -->
    <nav class="navbar">
        <h2>Lost & Found Portal</h2>
        <div class="hamburger" onclick="toggleNavbar()">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div class="navbar-links" id="navLinks">
            <a href="dashboard.php">Dashboard</a>
            <!--a href="post_item.php">Post Item</a>
            <a href="logout.php">Logout</a-->
        </div>
    </nav>

    <h1>My Posted Items</h1>

    <?php if (empty($userItems)): ?>
        <p style="text-align:center;">You haven't posted any items yet.</p>
    <?php else: ?>
        <div class="item-container">
            <?php foreach ($userItems as $item): ?>
                <div class="item-card">
                    <?php if (!empty($item['image_path'])): ?>
                        <img src="../uploads/<?php echo htmlspecialchars(basename($item['image_path'])); ?>" alt="Item Image" class="item-image" />
                    <?php endif; ?>
                    <p><strong>Item Name:</strong> <?= htmlspecialchars($item['item_name']) ?></p>
                    <p><strong>Category:</strong> <?= htmlspecialchars($item['category']) ?></p>
                    <p><strong>Description:</strong> <?= htmlspecialchars($item['description']) ?></p>
                    <p><strong>Date Found:</strong> <?= htmlspecialchars($item['date_found']) ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($item['status']) ?></p>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="./editItem.php?id=<?= $item['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="../../server/routes/deleteRoute.php?id=<?php echo $item['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- JavaScript for Responsive Navbar -->
    <script>
        // Toggle the navbar links on small screens
        function toggleNavbar() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('show');
        }
    </script>

</body>

</html>