<?php
require_once '../config/auth.php';
require_login();
require_role('Admin');

$userId = $_SESSION['user_id'];

$adminName = $_SESSION['fullname'];
$username = $_SESSION['username'];
$role = "Administrator";
$created = "";

$stmt = mysqli_prepare($conn, "SELECT fullname, username, created_at FROM users WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$userRow = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if ($userRow) {
    $adminName = $userRow['fullname'];
    $username = $userRow['username'];
    $created = date("d F Y", strtotime($userRow['created_at']));
}

$profileMessage = "";
$profileMessageType = "";

$passwordMessage = "";
$passwordMessageType = "";

if (isset($_POST['save'])) {
    $newFullname = trim($_POST['fullname'] ?? '');
    $newUsername = trim($_POST['username'] ?? '');

    if ($newFullname === '' || $newUsername === '') {
        $profileMessage = "Full name and username cannot be empty.";
        $profileMessageType = "error";
    } else {
        // Make sure the username isn't already taken by someone else
        $checkStmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? AND id != ? LIMIT 1");
        mysqli_stmt_bind_param($checkStmt, "si", $newUsername, $userId);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $profileMessage = "That username is already taken.";
            $profileMessageType = "error";
        } else {
            $updateStmt = mysqli_prepare($conn, "UPDATE users SET fullname = ?, username = ? WHERE id = ?");
            mysqli_stmt_bind_param($updateStmt, "ssi", $newFullname, $newUsername, $userId);
            mysqli_stmt_execute($updateStmt);
            mysqli_stmt_close($updateStmt);

            $_SESSION['fullname'] = $newFullname;
            $_SESSION['username'] = $newUsername;
            $adminName = $newFullname;
            $username = $newUsername;

            $profileMessage = "Account details updated successfully!";
            $profileMessageType = "success";
        }
        mysqli_stmt_close($checkStmt);
    }
}

if (isset($_POST['change_password'])) {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($new !== $confirm) {
        $passwordMessage = "New passwords do not match.";
        $passwordMessageType = "error";
    } elseif (strlen($new) < 8) {
        $passwordMessage = "New password must be at least 8 characters.";
        $passwordMessageType = "error";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if (!$user || !password_verify($current, $user['password'])) {
            $passwordMessage = "Current password is incorrect.";
            $passwordMessageType = "error";
        } else {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $updateStmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($updateStmt, "si", $hashed, $userId);
            mysqli_stmt_execute($updateStmt);
            mysqli_stmt_close($updateStmt);

            $passwordMessage = "Password updated successfully!";
            $passwordMessageType = "success";
        }
    }
}

function initials($name) {
  $parts = preg_split('/\s+/', trim($name));
  $letters = array_map(fn($p) => strtoupper(substr($p, 0, 1)), array_slice($parts, 0, 2));
  return implode('', $letters);
}

$pageTitle  = "Account Management";
$activeMenu = "account";
require 'includes/header.php';
?>

    <div class="card" style="margin-bottom:25px;">

      <h2>Administrator Profile</h2>

      <?php if ($profileMessage !== ""): ?>
        <div class="message <?php echo htmlspecialchars($profileMessageType); ?>" role="alert">
          <?php echo htmlspecialchars($profileMessage); ?>
        </div>
      <?php endif; ?>

      <div class="profile">
        <div class="avatar" aria-hidden="true"><?php echo htmlspecialchars(initials($adminName)); ?></div>
        <div>
          <h3><?php echo htmlspecialchars($adminName); ?></h3>
          <p><?php echo htmlspecialchars($role); ?></p>
        </div>
      </div>

      <form method="POST" novalidate>

        <div class="form-grid">

          <div class="form-group">
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($adminName); ?>" autocomplete="name" required>
          </div>

          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" autocomplete="username" required>
          </div>

          <div class="form-group">
            <label for="role">Role</label>
            <input class="readonly" type="text" id="role" value="<?php echo htmlspecialchars($role); ?>" readonly>
          </div>

          <div class="form-group">
            <label for="created">Account Created</label>
            <input class="readonly" type="text" id="created" value="<?php echo htmlspecialchars($created); ?>" readonly>
          </div>

        </div>

        <div class="buttons">
          <button class="primary" type="submit" name="save">
            <i class="fa fa-save"></i>
            Save Changes
          </button>
        </div>

      </form>
    </div>

    <div class="card">

      <h2>Change Password</h2>

      <?php if ($passwordMessage !== ""): ?>
        <div class="message <?php echo htmlspecialchars($passwordMessageType); ?>" role="alert">
          <?php echo htmlspecialchars($passwordMessage); ?>
        </div>
      <?php endif; ?>

      <form method="POST" id="passwordForm" novalidate>

        <div class="form-group">
          <label for="current_password">Current Password</label>
          <input type="password" id="current_password" name="current_password" placeholder="Enter current password" autocomplete="current-password" required>
        </div>

        <div class="form-group">
          <label for="new_password">New Password</label>
          <input type="password" id="new_password" name="new_password" placeholder="Enter new password" autocomplete="new-password" minlength="8" required>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm New Password</label>
          <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" autocomplete="new-password" minlength="8" required>
          <div id="mismatchError" style="display:none; color:#C0392B; font-size:13px; margin-top:6px;">Passwords do not match.</div>
        </div>

        <div class="buttons">
          <button class="primary" type="submit" name="change_password">
            <i class="fa fa-key"></i>
            Update Password
          </button>
        </div>

      </form>
    </div>

<?php
$pageScript = <<<'JS'
  const form = document.getElementById('passwordForm');
  const newPassword = document.getElementById('new_password');
  const confirmPassword = document.getElementById('confirm_password');
  const mismatchError = document.getElementById('mismatchError');

  if (form) {
    form.addEventListener('submit', (e) => {
      if (newPassword.value !== confirmPassword.value) {
        e.preventDefault();
        confirmPassword.style.borderColor = '#C0392B';
        mismatchError.style.display = 'block';
        confirmPassword.focus();
      }
    });

    confirmPassword.addEventListener('input', () => {
      confirmPassword.style.borderColor = '';
      mismatchError.style.display = 'none';
    });
  }
JS;
require 'includes/footer.php';
?>
