<?php
require_once '../config/auth.php';
require_login();
require_role('Student');

$studentName = $_SESSION['fullname'];
$studentId = $_SESSION['student_id'] ?? null;

$stats = [
  "result_requests" => 0,
  "approved"         => 0,
  "pending"          => 0,
  "notifications"    => 0,
];
$recentActivity = [];

if ($studentId) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS c FROM result_requests WHERE student_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $studentId);
    mysqli_stmt_execute($stmt);
    $stats["result_requests"] = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))["c"];
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS c FROM result_requests WHERE student_id = ? AND status = 'Approved'");
    mysqli_stmt_bind_param($stmt, "i", $studentId);
    mysqli_stmt_execute($stmt);
    $stats["approved"] = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))["c"];
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS c FROM result_requests WHERE student_id = ? AND status = 'Pending'");
    mysqli_stmt_bind_param($stmt, "i", $studentId);
    mysqli_stmt_execute($stmt);
    $stats["pending"] = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))["c"];
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS c FROM notifications WHERE student_id = ? AND is_read = 0");
    mysqli_stmt_bind_param($stmt, "i", $studentId);
    mysqli_stmt_execute($stmt);
    $stats["notifications"] = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))["c"];
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "SELECT request_date, status FROM result_requests WHERE student_id = ? ORDER BY request_date DESC LIMIT 5");
    mysqli_stmt_bind_param($stmt, "i", $studentId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $recentActivity[] = [
            "date"     => date("d M Y", strtotime($row["request_date"])),
            "activity" => "Result Request",
            "status"   => $row["status"],
        ];
    }
    mysqli_stmt_close($stmt);
}

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

      <a href="view_results.php" class="action">
        <i class="fa fa-list-check"></i>
        <h3>View Results</h3>
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
