<?php
include('../../../../server/includes/csrf_helper.php'); // Include CSRF helper functions
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once '../../../../server/config/db.php'; // Include your database connection file
require_once '../../../../server/classes/item_class.php'; // Include your Item model class


$db = new Database();
$conn = $db->connect();     // ✅ Get the PDO object
$item = new Item($conn);    // ✅ Pass the PDO connection to the Item class
$items = $item->getAllItems(); // ✅ Fetch all item

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header('Location: ../../login.html'); // Redirect to login page if not logged in or not an admin
  exit();
}
//check if user is login using the url to nevigate to the page
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../styles/adminDashboard.css" />
  <title>Admin Dashboard</title>
</head>

<body>


  <div class="container">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
      <div class="sidebar-header">
        <h2>Admin Panel</h2>
        <button class="close-btn" id="closeBtn">×</button>
      </div>
      <div class="user-info">
        <div class="user-avatar">
          <i>👤</i>
        </div>
        <div class="user-details">
          <h3>Admin User</h3>
          <p>admin@campus.edu</p>
        </div>
      </div>
      <div class="nav-menu">
        <a href="#" class="nav-item active">
          <i>📊</i>
          Dashboard
        </a>
        <a href="#" class="nav-item">
          <i>👥</i>
          User Management
        </a>
        <a href="./manageClaim.php" class="nav-item">
          <i>👥</i>
          Manage Claim
        </a>
        <a href="#" class="nav-item">
          <i>📦</i>
          Items
        </a>
        <a href="#" class="nav-item">
          <i>📝</i>
          Reports
        </a>
        <a href="#" class="nav-item">
          <i>📈</i>
          Analytics
        </a>
        <a href="#" class="nav-item">
          <i>⚙️</i>
          Settings
        </a>
      </div>
    </div>

    <!-- Toggle sidebar button -->
    <div class="toggle-btn" id="toggleBtn">
      <i>≡</i>
    </div>

    <!-- Main content -->
    <div class="main-content">
      <!-- Stats cards -->
      <div class="stats-container">
        <div class="stat-card">
          <div class="stat-info">
            <h3>Total Users</h3>
            <h2>2,345</h2>
            <div class="stat-change">+12% vs last month</div>
          </div>
          <div class="stat-icon">
            <i>👥</i>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-info">
            <h3>Active Items</h3>
            <h2>432</h2>
            <div class="stat-change">+8% vs last month</div>
          </div>
          <div class="stat-icon">
            <i>📦</i>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-info">
            <h3>Reports</h3>
            <h2>28</h2>
            <div class="stat-change">+3% vs last month</div>
          </div>
          <div class="stat-icon">
            <i>📝</i>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-info">
            <h3>Success Rate</h3>
            <h2>94%</h2>
            <div class="stat-change">+5% vs last month</div>
          </div>
          <div class="stat-icon">
            <i>📈</i>
          </div>
        </div>
      </div>

      <!-- Recent items table -->
      <div class="section-header">
        <h2 class="section-title">Recent Items</h2>
        <a href="#" class="view-all">View all</a>
      </div>
      <table class="items-table">
        <thead>
          <tr>
            <th>ITEM</th>
            <th>STATUS</th>
            <th>VIEW</th>
            <th>ACTION</th>
          </tr>
        </thead>
        <tbody>
          <!-- PHP code to fetch items from the database and display them in the table -->

          <?php foreach ($items as $item): ?>
            <tr>
              <td><?= htmlspecialchars($item['item_name']) ?></td>
              <td>
                <?php if (!isset($item['status'])): ?>
                  <span class="status-badge">Unknown</span>
                <?php else: ?>
                  <?php if ($item['status'] === 'pending'): ?>
                    <span class="status-badge status-pending">Pending</span>
                  <?php elseif ($item['status'] === 'approved'): ?>
                    <span class="status-badge status-approved">Approved</span>
                  <?php elseif ($item['status'] === 'claimed'): ?>
                    <span class="status-badge status-claimed">Claimed</span>
                  <?php else: ?>
                    <span class="status-badge status-rejected">Rejected</span>
                  <?php endif; ?>
                <?php endif; ?>
              </td>

              <td>
                <button
                  class="action-btn_ view-btn"
                  onclick="openModal(<?= htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8') ?>)">
                  👁
                </button>
              </td>

              <td>
                <?php if ($item['status'] === 'pending'): ?>
                  <form action="../../../../server/routes/item_routes.php" method="POST" style="display:inline;">
                    <input type="hidden" name="item_id" value="<?= htmlspecialchars($item['id']) ?>" />
                    <button type="submit" name="approve" class="action-btn approve-btn" title="Approve">✓</button>
                  </form>
                  <form action="../../../../server/routes/item_routes.php" method="POST" style="display:inline;">
                    <input type="hidden" name="item_id" value="<?= htmlspecialchars($item['id']) ?>" />
                    <button type="submit" name="reject" class="action-btn reject-btn" title="Reject">✕</button>
                  </form>
                <?php else: ?>
                  <span class="no-action">—</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>


          <!-- Add more rows as needed -->
        </tbody>
      </table>

      <!-- Content grid with notifications and users -->
      <div class="content-grid">
        <!-- Notifications section -->
        <div>
          <div class="section-header">
            <h2 class="section-title">Recent Notifications</h2>
          </div>
          <div class="notification-list">
            <div class="notification-item">
              <div class="notification-icon">
                <i>🔔</i>
              </div>
              <div class="notification-info">
                <h4>New Item Report</h4>
                <p>A new item has been reported</p>
                <div class="notification-time">5 min ago</div>
              </div>
            </div>
            <div class="notification-item">
              <div class="notification-icon">
                <i>🔔</i>
              </div>
              <div class="notification-info">
                <h4>User Verification</h4>
                <p>New user awaiting verification</p>
                <div class="notification-time">10 min ago</div>
              </div>
            </div>
            <div class="notification-item">
              <div class="notification-icon">
                <i>🔔</i>
              </div>
              <div class="notification-info">
                <h4>System Update</h4>
                <p>System maintenance scheduled</p>
                <div class="notification-time">1 hour ago</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent users section -->
        <div>
          <div class="section-header">
            <h2 class="section-title">Recent Users</h2>
            <a href="#" class="view-all">View all</a>
          </div>
          <div class="users-list">
            <div class="user-item">
              <div class="user-avatar-sm">
                <i>👤</i>
              </div>
              <div class="user-info-sm">
                <h4>John Doe</h4>
                <p>john@university.edu</p>
              </div>
              <span class="user-status">active</span>
            </div>
            <div class="user-item">
              <div class="user-avatar-sm">
                <i>👤</i>
              </div>
              <div class="user-info-sm">
                <h4>Sarah Smith</h4>
                <p>sarah@university.edu</p>
              </div>
              <span class="user-status">active</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  <div id="itemModal" class="modal" style="display:none;">
    <div class="modal-content">
      <span class="close-modal" onclick="closeModal()">×</span>
      <h2 id="modalItemName"></h2>
      <img id="modalItemImage" src="" alt="Item Image" style="max-width: 100%; height: auto;" />
      <p><strong>Location:</strong> <span id="modalItemLocation"></span></p>
      <p><strong>Date Found:</strong> <span id="modalItemDate"></span></p>
      <p><strong>Description:</strong> <span id="modalItemDescription"></span></p>
      <p><strong>Contact Name:</strong> <span id="modalContactName"></span></p>
      <p><strong>Contact Phone:</strong> <span id="modalContactEmail"></span></p>
      <p><strong>Unique Question:</strong> <span id="modalUniqueQuestion"></span></p>

    </div>
  </div>

  <script src="../script/adminDashboard.js"></script>
</body>

</html>