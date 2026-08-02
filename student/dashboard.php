<?php
session_start();

// Temporary user data (replace with database later)
$studentName = "Lonjezo Makhaula";

/* TODO (BACKEND): pull these stats from the database
   e.g. SELECT COUNT(*) FROM result_requests WHERE student_id = ?; etc. */
$stats = [
  "result_requests" => 0,
  "approved"         => 0,
  "pending"          => 0,
  "notifications"    => 0,
];

/* TODO (BACKEND): replace with a real query
   SELECT activity_date, activity, status FROM activity_log
   WHERE student_id = ? ORDER BY activity_date DESC LIMIT 10; */
$recentActivity = [];

$pageTitle  = "Dashboard";
$activeMenu = "dashboard";
require 'includes/header.php';
?>

    <div class="welcome">
      <h2>Welcome Back!</h2>
      <p>
        Hello <strong><?php echo htmlspecialchars($studentName); ?></strong>,
        welcome to the Student Portal.
        Use the menu to request examination results,
        track your requests and manage your profile.
      </p>
    </div>

    <div class="stat-cards">

      <div class="stat-card">
        <i class="fa fa-file-alt"></i>
        <h3><?php echo htmlspecialchars($stats["result_requests"]); ?></h3>
        <p>Result Requests</p>
      </div>

      <div class="stat-card">
        <i class="fa fa-check-circle"></i>
        <h3><?php echo htmlspecialchars($stats["approved"]); ?></h3>
        <p>Approved</p>
      </div>

      <div class="stat-card">
        <i class="fa fa-clock"></i>
        <h3><?php echo htmlspecialchars($stats["pending"]); ?></h3>
        <p>Pending</p>
      </div>

      <div class="stat-card">
        <i class="fa fa-bell"></i>
        <h3><?php echo htmlspecialchars($stats["notifications"]); ?></h3>
        <p>Notifications</p>
      </div>

    </div>

    <div class="actions">

      <a href="request_results.php" class="action">
        <i class="fa fa-file-signature"></i>
        <h3>Request Results</h3>
      </a>

      <a href="view_requests.php" class="action">
        <i class="fa fa-list-check"></i>
        <h3>My Requests</h3>
      </a>

      <a href="profile.php" class="action">
        <i class="fa fa-user-edit"></i>
        <h3>My Profile</h3>
      </a>

    </div>

    <div class="table-card">

      <h2>Recent Activity</h2>

      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Activity</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentActivity)): ?>
            <tr><td colspan="3" class="no-results">No activity yet — your requests will show up here.</td></tr>
          <?php else: ?>
            <?php foreach ($recentActivity as $item): ?>
              <tr>
                <td><?php echo htmlspecialchars($item["date"]); ?></td>
                <td><?php echo htmlspecialchars($item["activity"]); ?></td>
                <td><?php echo htmlspecialchars($item["status"]); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>

    </div>

<?php require 'includes/footer.php'; ?>
