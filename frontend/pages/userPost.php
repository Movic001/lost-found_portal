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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Posted Items</title>
</head>

<body>
    <h1>My Posted Items</h1>

    <?php if (empty($userItems)): ?>
        <p>You haven't posted any items yet.</p>
    <?php else: ?>
        <?php foreach ($userItems as $item): ?>
            <div>
                <p><strong>Item Name:</strong> <?= htmlspecialchars($item['name']) ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($item['category']) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($item['description']) ?></p>
                <p><strong>Date Found:</strong> <?= htmlspecialchars($item['date_found']) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($item['status']) ?></p>

                <!-- You can also add edit/delete buttons -->
                <a href="edit_item.php?id=<?= $item['id'] ?>">Edit</a>
                <a href="delete_item.php?id=<?= $item['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</body>

</html>