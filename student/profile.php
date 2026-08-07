<?php
require_once '../config/auth.php';
require_login();
require_role('Student');

$studentId = $_SESSION['student_id'] ?? null;

$studentName = $_SESSION['fullname'];
$username = $_SESSION['username'];
$college = "Not set";
$course = "Not set";
$level = "Not set";
$accountStatus = "Active";

if ($studentId) {
    $stmt = mysqli_prepare($conn, "
        SELECT col.college_name, co.course_name, sem.semester_name, u.status
        FROM students s
        JOIN colleges col ON s.college_id = col.college_id
        JOIN courses co ON s.course_id = co.course_id
        JOIN semesters sem ON s.semester_id = sem.semester_id
        JOIN users u ON s.user_id = u.id
        WHERE s.student_id = ?
    ");
    mysqli_stmt_bind_param($stmt, "i", $studentId);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if ($row) {
        $college = $row['college_name'];
        $course = $row['course_name'];
        $level = ucfirst($row['semester_name']);
        $accountStatus = $row['status'];
    }
}

function initials($name) {
  $parts = preg_split('/\s+/', trim($name));
  $letters = array_map(fn($p) => strtoupper(substr($p, 0, 1)), array_slice($parts, 0, 2));
  return implode('', $letters);
}

$pageTitle  = "My Profile";
$activeMenu = "profile";
require 'includes/header.php';
?>

    <div class="card">

      <div class="profile-top">
        <div class="avatar" aria-hidden="true"><?php echo htmlspecialchars(initials($studentName)); ?></div>
        <h2><?php echo htmlspecialchars($studentName); ?></h2>
        <p>TEVETA Student</p>
      </div>

      <div class="info-grid">

        <div class="info-box">
          <label>Full Name</label>
          <p><?php echo htmlspecialchars($studentName); ?></p>
        </div>

        <div class="info-box">
          <label>Username</label>
          <p><?php echo htmlspecialchars($username); ?></p>
        </div>

        <div class="info-box">
          <label>College</label>
          <p><?php echo htmlspecialchars($college); ?></p>
        </div>

        <div class="info-box">
          <label>Course</label>
          <p><?php echo htmlspecialchars($course); ?></p>
        </div>

        <div class="info-box">
          <label>Level</label>
          <p><?php echo htmlspecialchars($level); ?></p>
        </div>

        <div class="info-box">
          <label>Account Status</label>
          <p>
            <span class="status <?php echo strtolower($accountStatus) === 'active' ? 'status-active' : 'status-suspended'; ?>">
              <?php echo htmlspecialchars($accountStatus); ?>
            </span>
          </p>
        </div>

      </div>

      <div class="buttons">
        <a href="edit_profile.php" class="btn edit">
          <i class="fa fa-pen"></i>
          Edit Profile
        </a>

        <a href="dashboard.php" class="btn back">
          <i class="fa fa-arrow-left"></i>
          Back to Dashboard
        </a>
      </div>

    </div>

<?php require 'includes/footer.php'; ?>
