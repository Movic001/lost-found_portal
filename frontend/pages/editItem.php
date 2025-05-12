<?php
require_once(__DIR__ . '../../../server/controller/edit_itemsController.php');
require_once(__DIR__ . '/../../server/config/db.php');
session_start();

$editItemController = new EditItemController($db);

// Ensure ID is provided
if (!isset($_GET['id'])) {
  echo "<script>alert('No item ID provided.'); window.location.href='./view_items.php';</script>";
  exit;
}

$item = $editItemController->getItemById($_GET['id']);

// Ensure the user is the owner
if (!$item || !$editItemController->checkItemOwner($_GET['id'], $_SESSION['user_id'])) {
  echo "<script>alert('Unauthorized access.'); window.location.href='./view_items.php';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Edit Found Item</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../styles/editItems.css" />
</head>

<body>
  <div class="navbar">
    <h1>Edit Found Item</h1>
    <span class="toggle_back"><a href="./view_items.php"> Back to Items</a></span>
  </div>

  <div class="form-container">
    <h2>Edit Item Details</h2>
    <form method="POST" action="../../server/routes/edit_itemRoute.php" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>" />

      <input type="text" name="item_name" placeholder="Item Name" value="<?= htmlspecialchars($item['item_name']) ?>" required />

      <input type="text" name="category" placeholder="Category" value="<?= htmlspecialchars($item['category']) ?>" required />

      <textarea name="description" placeholder="Description" rows="4" required><?= htmlspecialchars($item['description']) ?></textarea>

      <input type="text" name="location_found" placeholder="Location Found" value="<?= htmlspecialchars($item['location_found']) ?>" required />

      <input type="date" name="date_found" value="<?= htmlspecialchars($item['date_found']) ?>" required />

      <input type="text" name="person_name" placeholder="Your Name" value="<?= htmlspecialchars($item['person_name']) ?>" required />

      <input type="number" name="contact_info" placeholder="Contact Information" value="<?= htmlspecialchars($item['contact_info']) ?>" required />

      <!-- Existing Image -->
      <?php if (!empty($item['image_path'])): ?>
        <img src="../../frontend/uploads/<?= $item['image_path'] ?>" alt="Current Image" style="width: 100px; height: auto; margin-bottom: 10px" />
      <?php endif; ?>
      <input type="hidden" name="existing_image_path" value="<?= htmlspecialchars($item['image_path']) ?>" />
      <input type="file" name="image_path" accept="image/*" />

      <!-- Unique Question field -->
      <input type="text" name="unique_question" placeholder="Unique Question" value="<?= htmlspecialchars($item['unique_question']) ?>" required />

      <button type="submit" name="postItem">Update Item</button>
    </form>
  </div>
</body>

</html>