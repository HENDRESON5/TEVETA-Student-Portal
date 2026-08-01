<?php
session_start();

// Temporary admin data
$adminName = "System Administrator";
$username = "admin";
$role = "Administrator";
$created = "01 January 2026";

$profileMessage = "";
$profileMessageType = "";

$passwordMessage = "";
$passwordMessageType = "";

if (isset($_POST['save'])) {
  /* TODO (BACKEND):
     - Validate $_POST['fullname'] and $_POST['username']
     - Check the username isn't already taken by another admin
     - Save changes to the database
     - Set $profileMessage / $profileMessageType based on the actual result */
  $profileMessage = "Account details updated successfully! (Database integration coming later)";
  $profileMessageType = "success";
}

if (isset($_POST['change_password'])) {
  /* TODO (BACKEND):
     - Verify $_POST['current_password'] against the DB hash
     - Confirm $_POST['new_password'] === $_POST['confirm_password']
     - Hash and save the new password
     - Set $passwordMessage / $passwordMessageType based on the actual result,
       e.g. on failure: $passwordMessage = "Current password is incorrect.";
                        $passwordMessageType = "error"; */
  $passwordMessage = "Password updated successfully! (Database integration coming later)";
  $passwordMessageType = "success";
}

// Small helper for the avatar - shows the admin's initials
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
  // Client-side password match check only.
  // Real validation must still happen in PHP, since front-end checks can be bypassed.
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
