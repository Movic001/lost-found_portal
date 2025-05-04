<?php
session_start();
require_once(__DIR__ . '/../../server/config/db.php');
require_once(__DIR__ . '/../../server/model/postItem_class.php');

// Instantiate FoundItem object
$foundItem = new FoundItem($db);

// Handle search input
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($searchQuery)) {
  $items = $foundItem->searchApprovedItems($searchQuery);
} else {
  $items = $foundItem->getAllItems();
}

if (!empty($searchQuery) && empty($items)) {
  echo "<script>alert('No items found'); window.location.href='view_items.php';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../styles/view_items.css" />
  <title>View Lost Items</title>
</head>

<body>
  <div class="navbar">
    <a href="./dashboard.html">
      <h1>Found Items</h1>
    </a>
    <div class="search-bar">
      <form method="GET" action="">
        <input
          type="text"
          name="search"
          placeholder="Search by item name or category..."
          value="<?php echo htmlspecialchars($searchQuery); ?>" />
        <button type="submit">Search</button>
      </form>
    </div>
  </div>

  <div class="items-grid">
    <?php foreach ($items as $item): ?>
      <div class="item-card">
        <?php if (!empty($item['image_path'])): ?>
          <img src="../uploads/<?php echo htmlspecialchars(basename($item['image_path'])); ?>" alt="Item Image" class="item-image" />
        <?php endif; ?>

        <div class="item-info">
          <h3 class="item-name"><?php echo htmlspecialchars($item['item_name']); ?></h3>
          <p class="item-category"><?php echo htmlspecialchars($item['category']); ?></p>
          <p class="item-details">Location: <?php echo htmlspecialchars($item['location_found']); ?></p>
          <p class="item-date">Found: <?php echo htmlspecialchars($item['date_found']); ?></p>

          <div class="user-info">
            <img src="../assets/image/profile_img/default_user.png" alt="User" class="user-avatar" />
            <span>Posted by <?php echo htmlspecialchars($item['person_name']); ?></span>
          </div>

          <div class="item-actions">
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $item['user_id']): ?>
              <a href="./editItem.php?id=<?= $item['id'] ?>" class="btn btn-edit">Edit</a>
              <a href="../../server/routes/deleteRoute.php?id=<?php echo $item['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
            <?php else: ?>
              <a href="claim_item.php?id=<?php echo $item['id']; ?>" class="btn btn-claim">Claim</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</body>

</html>