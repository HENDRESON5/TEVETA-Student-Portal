<?php
session_start();

// Temporary user
$studentName = "Lonjezo Makhaula";

$message = "";
$messageType = "";

if(isset($_POST['change_password'])){
    /* TODO (BACKEND):
       - Verify $_POST['current_password'] against the DB hash
       - Confirm $_POST['new_password'] === $_POST['confirm_password']
       - Hash and save the new password
       - Set $message / $messageType based on the actual result */
    $message = "Password updated successfully! (Database connection coming later)";
    $messageType = "success";
}

$pageTitle  = "Change Password";
$activeMenu = "password";
require 'includes/header.php';
?>

    <div class="card">

      <h2>Update Your Password</h2>

      <?php if ($message !== ""): ?>
        <div class="message <?php echo htmlspecialchars($messageType); ?>" role="alert">
          <?php echo htmlspecialchars($message); ?>
        </div>
      <?php endif; ?>

      <form method="POST" id="passwordForm" novalidate>

        <div class="form-group">
          <label for="current_password">Current Password</label>
          <div class="password-wrap">
            <input type="password" id="current_password" name="current_password" placeholder="Enter current password" autocomplete="current-password" required>
            <button type="button" class="toggle-password" data-target="current_password" aria-label="Show current password">Show</button>
          </div>
        </div>

        <div class="form-group">
          <label for="new_password">New Password</label>
          <div class="password-wrap">
            <input type="password" id="new_password" name="new_password" placeholder="Enter new password" autocomplete="new-password" minlength="8" required>
            <button type="button" class="toggle-password" data-target="new_password" aria-label="Show new password">Show</button>
          </div>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm New Password</label>
          <div class="password-wrap">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" autocomplete="new-password" minlength="8" required>
            <button type="button" class="toggle-password" data-target="confirm_password" aria-label="Show confirm password">Show</button>
          </div>
          <div class="field-error" id="mismatchError">Passwords do not match.</div>
        </div>

        <button type="submit" name="change_password">
          <i class="fa fa-key"></i>
          Update Password
        </button>

      </form>
    </div>

    <div class="comment-box" style="margin-top:30px;">
      <h3>Password Tips</h3>
      <ul style="padding-left:20px; color:#555; line-height:1.7;">
        <li>Use at least 8 characters.</li>
        <li>Include uppercase and lowercase letters.</li>
        <li>Include at least one number.</li>
        <li>Use a special character (e.g. @, #, $, %).</li>
        <li>Do not share your password with anyone.</li>
      </ul>
    </div>

<?php
$pageScript = <<<'JS'
  document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = document.getElementById(btn.dataset.target);
      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      btn.textContent = isHidden ? 'Hide' : 'Show';
    });
  });

  const form = document.getElementById('passwordForm');
  const newPassword = document.getElementById('new_password');
  const confirmPassword = document.getElementById('confirm_password');
  const mismatchError = document.getElementById('mismatchError');

  if (form) {
    form.addEventListener('submit', (e) => {
      if (newPassword.value !== confirmPassword.value) {
        e.preventDefault();
        confirmPassword.classList.add('mismatch');
        mismatchError.classList.add('show');
        confirmPassword.focus();
      }
    });

    confirmPassword.addEventListener('input', () => {
      confirmPassword.classList.remove('mismatch');
      mismatchError.classList.remove('show');
    });
  }
JS;
require 'includes/footer.php';
?>
