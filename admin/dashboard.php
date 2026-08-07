<?php
require_once '../config/auth.php';
require_login();
require_role('Admin');

$adminName = $_SESSION['fullname'];

$stats = [
  "total_students"   => 0,
  "result_requests"  => 0,
  "pending_requests" => 0,
  "results_uploaded" => 0,
];

$stats["total_students"] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM students"))["c"];
$stats["result_requests"] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM result_requests"))["c"];
$stats["pending_requests"] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM result_requests WHERE status = 'Pending'"))["c"];
$stats["results_uploaded"] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM results"))["c"];

$recentRequests = [];
$result = mysqli_query($conn, "
    SELECT r.request_id, u.fullname AS student, col.college_name, co.course_name, sem.semester_name, r.status
    FROM result_requests r
    JOIN students s ON r.student_id = s.student_id
    JOIN users u ON s.user_id = u.id
    JOIN colleges col ON s.college_id = col.college_id
    JOIN courses co ON s.course_id = co.course_id
    JOIN semesters sem ON s.semester_id = sem.semester_id
    ORDER BY r.request_date DESC
    LIMIT 5
");
while ($row = mysqli_fetch_assoc($result)) {
    $recentRequests[] = [
        "id"      => $row["request_id"],
        "student" => $row["student"],
        "college" => $row["college_name"],
        "course"  => $row["course_name"],
        "level"   => ucfirst($row["semester_name"]),
        "status"  => strtolower($row["status"]),
    ];
}

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
