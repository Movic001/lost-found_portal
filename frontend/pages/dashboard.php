<?php
session_start();
require_once('../../server/config/db.php');
require_once(__DIR__ . '/../../server/classes/postItem_class.php');
require_once('../../server/includes/auth.php');

$database = new Database();
$db = $database->connect(); // ✅ Get the PDO connection


$foundItem = new FoundItem($db);
$items = $foundItem->getAllItems();

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
          <?php echo htmlspecialchars($user['userName']); ?>
        </p>
        <p>
          <strong>Mobile:</strong>
          <?php echo htmlspecialchars($user['mobile']); ?>
        </p>

        <p>
          <strong>Email:</strong>
          <?php echo htmlspecialchars($user['email']); ?>
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
      <a href="#" class="nav-item"> <i class="fas fa-list"></i> My Posts </a>
      <a href="#" class="nav-item">
        <i class="fas fa-bell"></i> Notifications
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
      <h1 class="welcome-message">Welcome, <span> <?php echo htmlspecialchars($user['userName']); ?></span> </h1>
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
        <button class="action-btn">
          <i class="fas fa-list"></i> View My Posts
        </button>
      </div>
    </div>

    <div class="stats-section">
      <div class="stat-card">
        <div class="stat-number">24</div>
        <div class="stat-label">Total Items Found</div>
      </div>
      <div class="stat-card">
        <div class="stat-number">16</div>
        <div class="stat-label">Items Claimed</div>
      </div>
      <div class="stat-card">
        <div class="stat-number">8</div>
        <div class="stat-label">Still Unclaimed</div>
      </div>
      <div class="stat-card">
        <div class="stat-number">30</div>
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
                <a href="edit_item.php?id=<?php echo $item['id']; ?>"><button class="btn btn-edit">Edit</button></a>
                <form method="POST" action="../../server/routes/deleteRoute.php" style="display:inline;">
                  <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                  <button class="btn btn-delete" onclick="return confirm('Delete this item?')">Delete</button>
                </form>
              <?php else: ?>
                <form method="POST" action="claim_item.php" style="display:inline;">
                  <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">

                  <a href="../../server/routes/claimRoute.php?item_id=<?= $item['id'] ?>" class="btn btn-claim">Claim</a>

                </form>
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