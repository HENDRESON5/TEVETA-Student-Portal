<?php
require_once '../config/auth.php';
require_login();
require_role('Admin');

$adminName = $_SESSION['fullname'];
$adminId = $_SESSION['user_id'];

$message = "";
$messageType = "";

if (isset($_POST['reset_password'])) {
    $requestId = (int)$_POST['request_id'];
    $userId = (int)$_POST['user_id'];
    $newPassword = $_POST['new_password'] ?? '';

    if (strlen($newPassword) < 8) {
        $message = "New password must be at least 8 characters.";
        $messageType = "error";
    } else {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $hashed, $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $stmt2 = mysqli_prepare($conn, "UPDATE password_reset_requests SET status = 'Resolved', resolved_by = ?, resolved_at = NOW() WHERE request_id = ?");
        mysqli_stmt_bind_param($stmt2, "ii", $adminId, $requestId);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);

        $message = "Password reset successfully. Let the student know their new password.";
        $messageType = "success";
    }
}

$requests = [];
$result = mysqli_query($conn, "
    SELECT p.request_id, p.user_id, u.fullname, u.username, p.requested_at, p.status
    FROM password_reset_requests p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.status = 'Pending' DESC, p.requested_at DESC
");
while ($row = mysqli_fetch_assoc($result)) {
    $requests[] = $row;
}

$pageTitle  = "Password Reset Requests";
$activeMenu = "resets";
require 'includes/header.php';
?>

    <?php if ($message !== ""): ?>
      <div class="message <?php echo htmlspecialchars($messageType); ?>" role="alert" style="margin-bottom:20px;">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <div class="table-card">

      <h2>Requests</h2>

      <table>
        <thead>
          <tr>
            <th>Student</th>
            <th>Username</th>
            <th>Requested</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($requests)): ?>
            <tr><td colspan="5" class="no-results">No password reset requests.</td></tr>
          <?php else: ?>
            <?php foreach ($requests as $req): ?>
              <tr>
                <td><?php echo htmlspecialchars($req["fullname"]); ?></td>
                <td><?php echo htmlspecialchars($req["username"]); ?></td>
                <td><?php echo date("d M Y, H:i", strtotime($req["requested_at"])); ?></td>
                <td>
                  <span class="status <?php echo strtolower($req["status"]) === 'pending' ? 'status-pending' : 'status-approved'; ?>">
                    <?php echo htmlspecialchars($req["status"]); ?>
                  </span>
                </td>
                <td>
                  <?php if (strtolower($req["status"]) === 'pending'): ?>
                    <form method="POST" style="display:flex; gap:8px; align-items:center;">
                      <input type="hidden" name="request_id" value="<?php echo (int)$req['request_id']; ?>">
                      <input type="hidden" name="user_id" value="<?php echo (int)$req['user_id']; ?>">
                      <input type="password" name="new_password" placeholder="New password" minlength="8" required style="width:150px; padding:8px;">
                      <button type="submit" name="reset_password" class="view" style="cursor:pointer; border:none;">Reset</button>
                    </form>
                  <?php else: ?>
                    <span style="color:#777; font-size:13px;">Resolved</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>

    </div>

<?php require 'includes/footer.php'; ?>
