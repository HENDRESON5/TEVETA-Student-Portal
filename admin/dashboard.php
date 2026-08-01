<?php
session_start();

// Temporary admin data
$adminName = "System Administrator";

/* TODO (BACKEND): pull these stats from the database
   e.g. SELECT COUNT(*) FROM students; SELECT COUNT(*) FROM result_requests WHERE status='pending'; etc. */
$stats = [
  "total_students"   => 250,
  "result_requests"  => 65,
  "pending_requests" => 18,
  "results_uploaded" => 47,
];

/* TODO (BACKEND): replace with a real query, most recent first, limited to e.g. 5-10 rows
   SELECT s.name, s.college, s.course, s.level, r.status, r.id
   FROM result_requests r JOIN students s ON r.student_id = s.id
   ORDER BY r.created_at DESC LIMIT 10; */
$recentRequests = [
  ["student" => "Lonjezo Makhaula", "college" => "Salima Technical College", "course" => "ICT",                     "level" => "Level 2", "status" => "pending",  "id" => 101],
  ["student" => "John Banda",       "college" => "Mzuzu Technical College",  "course" => "Electrical Installation",  "level" => "Level 3", "status" => "approved", "id" => 102],
];

function statusClass($status) {
  return strtolower($status) === "approved" ? "status-approved" : "status-pending";
}

$pageTitle  = "Dashboard";
$activeMenu = "dashboard";
require 'includes/header.php';
?>

    <div class="welcome">
      <h2>Welcome Administrator 👋</h2>
      <p>
        Manage student accounts, review examination result requests,
        upload results and monitor activities from this dashboard.
      </p>
    </div>

    <div class="stat-cards">

      <div class="stat-card">
        <i class="fa fa-users"></i>
        <h1><?php echo htmlspecialchars($stats["total_students"]); ?></h1>
        <p>Total Students</p>
      </div>

      <div class="stat-card">
        <i class="fa fa-file-alt"></i>
        <h1><?php echo htmlspecialchars($stats["result_requests"]); ?></h1>
        <p>Result Requests</p>
      </div>

      <div class="stat-card">
        <i class="fa fa-clock"></i>
        <h1><?php echo htmlspecialchars($stats["pending_requests"]); ?></h1>
        <p>Pending Requests</p>
      </div>

      <div class="stat-card">
        <i class="fa fa-check-circle"></i>
        <h1><?php echo htmlspecialchars($stats["results_uploaded"]); ?></h1>
        <p>Results Uploaded</p>
      </div>

    </div>

    <div class="actions">

      <a href="students.php" class="action">
        <i class="fa fa-users"></i>
        <h3>Students</h3>
        <p>View registered students.</p>
      </a>

      <a href="result_requests.php" class="action">
        <i class="fa fa-file-lines"></i>
        <h3>Result Requests</h3>
        <p>Review requests and enter results.</p>
      </a>

      <a href="account_management.php" class="action">
        <i class="fa fa-cog"></i>
        <h3>Account</h3>
        <p>Manage your admin account.</p>
      </a>

    </div>

    <div class="table-card">

      <h2>Recent Result Requests</h2>

      <table>
        <thead>
          <tr>
            <th>Student</th>
            <th>College</th>
            <th>Course</th>
            <th>Level</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentRequests)): ?>
            <tr><td colspan="6" class="no-results">No result requests yet.</td></tr>
          <?php else: ?>
            <?php foreach ($recentRequests as $req): ?>
              <tr>
                <td><?php echo htmlspecialchars($req["student"]); ?></td>
                <td><?php echo htmlspecialchars($req["college"]); ?></td>
                <td><?php echo htmlspecialchars($req["course"]); ?></td>
                <td><?php echo htmlspecialchars($req["level"]); ?></td>
                <td><span class="status <?php echo statusClass($req["status"]); ?>"><?php echo htmlspecialchars(ucfirst($req["status"])); ?></span></td>
                <td><a href="result_requests.php?action=edit&amp;request_id=<?php echo (int)$req['id']; ?>" class="view">View</a></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>

    </div>

<?php require 'includes/footer.php'; ?>
