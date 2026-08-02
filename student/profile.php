<?php
session_start();

// Temporary student data
$studentName = "Lonjezo Makhaula";
$username = "lonjezo";
$college = "Salima Technical College";
$course = "ICT";
$level = "Level 2";

// TODO (BACKEND): pull account status from DB (e.g. "Active" / "Suspended")
$accountStatus = "Active";

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
        <!-- TODO (BACKEND): point this to your actual edit-profile page/handler -->
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
