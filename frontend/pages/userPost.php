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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posted Items</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/userPost.css">
</head>

<body>
    <h1>My Posted Items</h1>

    <?php if (empty($userItems)): ?>
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p>You haven't posted any items yet.</p>
        </div>
    <?php else: ?>
        <?php foreach ($userItems as $item): ?>
            <div>
                <span class="status-indicator <?= strtolower($item['status']) === 'found' ? 'status-found' : (strtolower($item['status']) === 'claimed' ? 'status-claimed' : 'status-pending') ?>">
                    <?= htmlspecialchars($item['status']) ?>
                </span>
                <?php if (!empty($item['image_path'])): ?>
                    <img src="../uploads/<?php echo htmlspecialchars(basename($item['image_path'])); ?>" alt="Item Image" class="item-image" />
                <?php endif; ?>
                <p><strong>Item Name:</strong> <?= htmlspecialchars($item['item_name']) ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($item['category']) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($item['description']) ?></p>
                <p><strong>Date Found:</strong> <?= htmlspecialchars($item['date_found']) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($item['status']) ?></p>

                <div class="action-buttons">
                    <a href="edit_item.php?id=<?= $item['id'] ?>" aria-label="Edit <?= htmlspecialchars($item['item_name']) ?>">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="delete_item.php?id=<?= $item['id'] ?>" onclick="return confirmDelete(event, '<?= htmlspecialchars($item['item_name']) ?>')" aria-label="Delete <?= htmlspecialchars($item['item_name']) ?>">
                        <i class="fas fa-trash-alt"></i> Delete
                    </a>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
    <script src="../script/userPost.js"></script>
</body>

</html>