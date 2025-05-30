<?php
//if session is not started, start it
if (session_status() == PHP_SESSION_NONE) {
  session_start(); // Only start the session if it's not already active
}
include('../../server/includes/auth.php');
require_once('../../server/config/db.php');
require_once(__DIR__ . '/../../server/classes/postItem_class.php');
require_once('../../server/includes/auth.php');

$database = new Database();
$db = $database->connect(); // ✅ Get the PDO connection


$foundItem = new FoundItem($db);
$items = $foundItem->getAllItems();


$foundItemController = new FoundItem($db);
// Fetch only the current user's items
$userId = $_SESSION['user_id'];
$userItems = $foundItemController->getItemsByUserId($userId);
$postCount = count($userItems);
$claimedCount = $foundItemController->countClaimedItemsByUser($userId);
$unclaimedCount = $foundItemController->countUnclaimedItemsByUser($userId);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Campus Lost and Found Portal</title>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="../styles/dashboard.css" />
</head>

<body>
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="user-profile">
      <img
        src="../assets/image/profile_img/profile.webp"
        alt="User"
        class="user-avatar" />
      <h3>User Profile</h3>
      <div class="dropdown-content">
        <p>
          <strong>Name:</strong>
          <?php echo htmlspecialchars($_SESSION['user_name']); ?>
        </p>
        <p>
          <strong>Mobile:</strong>
          <?php echo htmlspecialchars($_SESSION['user_mobile']); ?>
        </p>

        <p>
          <strong>Email:</strong>
          <?php echo htmlspecialchars($_SESSION['user_email']); ?>
        </p>

      </div>
      <form action="../../server/routes/logOut.php" method="POST">
        <button class="logout-btn" type="submit" name="logOut">
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
      </form>

    </div>

    <div class="sidebar-nav">
      <a href="#" class="nav-item active">
        <i class="fas fa-home"></i> Dashboard
      </a>
      <a href="./post_item.html" class="nav-item">
        <i class="fas fa-plus-circle"></i> Post Found Item
      </a>
      <a href="./view_items.php" class="nav-item">
        <i class="fas fa-search"></i> Browse Items
      </a>
      <a href="./claimResult.php" class="nav-item">
        <i class="fas fa-bell"></i>Claim Notifications
      </a>
      <a href="./manageClaim.php" class="nav-item">
        <i class="fas fa-bell"></i> Manage Claims
      </a>
      <a href="#" class="nav-item"> <i class="fas fa-cog"></i> Settings </a>
    </div>
  </div>

  <!-- Overlay for mobile -->
  <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

  <!-- Navbar -->
  <div class="navbar" id="navbar">
    <h1>Lost & Found Portal</h1>
    <div class="toggle-btn" onclick="toggleSidebar()">
      <i class="fas fa-bars"></i>
    </div>
  </div>

  <!-- Main Content -->
  <div class="container" id="container">
    <div class="welcome-section">
      <h1 class="welcome-message">Welcome, <span> <?php echo htmlspecialchars($_SESSION['user_name']); ?></span> </h1>
      <div class="quick-actions">
        <a href="./post_item.html">
          <button class="action-btn">
            <i class="fas fa-plus-circle"></i> Post a Found Item
          </button>
        </a>
        <a href="./view_items.php">
          <button class="action-btn">
            <i class="fas fa-search"></i> View Found Items
          </button>
        </a>
        <a href="./userPost.php">
          <button class="action-btn">
            <i class="fas fa-list"></i> View My Posts
          </button>
        </a>
      </div>
    </div>

    <div class="stats-section">
      <!--div class="stat-card">
        <div class="stat-number">24</div>
        <div class="stat-label">Total Items Found</div>
      </div-->
      <div class="stat-card">
        <div class="stat-number"><?php echo $claimedCount; ?></div>
        <div class="stat-label">Items Claimed</div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?php echo $unclaimedCount; ?></div>
        <div class="stat-label">Still Unclaimed</div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?php echo $postCount; ?></div>
        <div class="stat-label">Your Posts</div>
      </div>
    </div>

    <h2 class="section-title">Recent Found Items</h2>
    <div class="items-grid">
      <!--Loop Through Items From Database:-->
      <?php foreach ($items as $item): ?>
        <div class="item-card">
          <img src="../uploads/<?php echo htmlspecialchars(basename($item['image_path'])); ?>" alt="Item Image" class="item-image" />
          <div class="item-info">
            <h3 class="item-name"><?php echo $item['item_name']; ?></h3>
            <p class="item-category"><?php echo $item['category']; ?></p>

            <p class="item-date">Found: <?php echo $item['date_found']; ?></p>
            <div class="user-info">
              <img src="../assets/image/profile_img/profile.webp" alt="User" class="user-avatar" />

              <span>Posted by <?php echo htmlspecialchars($item['person_name']); ?> on <?php echo htmlspecialchars($item['created_at']); ?> </span>
            </div>
            <div class="item-actions">
              <!-- check if the user is the owner of the post-->
              <?php if ($item['user_id'] === $_SESSION['user_id']): ?>
                <a href="./editItem.php?id=<?= $item['id'] ?>"><button class="btn btn-edit">Edit</button></a>
                <a href="../../server/routes/deleteRoute.php?id=<?php echo $item['id']; ?>" class="btn btn-delete">Delete</a>
              <?php else: ?>
                <a href="../../server/routes/claimRoute.php?item_id=<?= $item['id'] ?>" class="btn btn-claim">Claim</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>



    </div>
  </div>

  <footer id="footer">
    <p>© 2025 Campus Lost & Found Portal | Developed by Ibrahim Abdulkerim</p>
  </footer>
  <script src="../script/dashboard.js"></script>
</body>

</html>