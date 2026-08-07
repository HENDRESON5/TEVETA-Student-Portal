<?php
require_once '../config/auth.php';
require_login();
require_role('Student');

$studentName = $_SESSION['fullname'];
$studentId = $_SESSION['student_id'] ?? null;

$message = "";
$messageType = "";

// Pull the student's own college/course for display + to build the request note
$profile = null;
if ($studentId) {
    $stmt = mysqli_prepare($conn, "
        SELECT col.college_name, co.course_name, sem.semester_name
        FROM students s
        JOIN colleges col ON s.college_id = col.college_id
        JOIN courses co ON s.course_id = co.course_id
        JOIN semesters sem ON s.semester_id = sem.semester_id
        WHERE s.student_id = ?
    ");
    mysqli_stmt_bind_param($stmt, "i", $studentId);
    mysqli_stmt_execute($stmt);
    $profile = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
}

if (isset($_POST['request_results']) && $studentId) {

    // Check there isn't already a pending request, to match the front-end's
    // "one request at a time" note in the Important Information box.
    $checkStmt = mysqli_prepare($conn, "SELECT request_id FROM result_requests WHERE student_id = ? AND status = 'Pending' LIMIT 1");
    mysqli_stmt_bind_param($checkStmt, "i", $studentId);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        $message = "You already have a pending request. Please wait for it to be reviewed before submitting another.";
        $messageType = "error";
    } else {
        $examYear = (int)($_POST['exam_year'] ?? date('Y'));
        $note = "Requested results for " . ($profile['course_name'] ?? 'their course') .
                " (" . ucfirst($profile['semester_name'] ?? '') . ") at " .
                ($profile['college_name'] ?? 'their college') . " - Exam Year: " . $examYear . ".";

        $insertStmt = mysqli_prepare($conn, "INSERT INTO result_requests (student_id, message) VALUES (?, ?)");
        mysqli_stmt_bind_param($insertStmt, "is", $studentId, $note);
        mysqli_stmt_execute($insertStmt);
        mysqli_stmt_close($insertStmt);

        $message = "Your result request has been submitted successfully.";
        $messageType = "success";
    }
    mysqli_stmt_close($checkStmt);
}

$pageTitle  = "Request Examination Results";
$activeMenu = "request";
require 'includes/header.php';
?>

    <div class="card">

      <h2>Request Form</h2>

      <?php if ($message !== ""): ?>
        <div class="message <?php echo htmlspecialchars($messageType); ?>" role="alert">
          <?php echo htmlspecialchars($message); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="" novalidate>

        <div class="form-grid">

          <div class="form-group">
            <label>College</label>
            <input type="text" value="<?php echo htmlspecialchars($profile['college_name'] ?? 'Not set'); ?>" class="readonly" readonly>
          </div>

          <div class="form-group">
            <label>Course</label>
            <input type="text" value="<?php echo htmlspecialchars($profile['course_name'] ?? 'Not set'); ?>" class="readonly" readonly>
          </div>

          <div class="form-group">
            <label>Level</label>
            <input type="text" value="<?php echo htmlspecialchars(ucfirst($profile['semester_name'] ?? 'Not set')); ?>" class="readonly" readonly>
          </div>

          <div class="form-group">
            <label for="exam_year">Examination Year</label>
            <select id="exam_year" name="exam_year" required>
              <option value="2026">2026</option>
              <option value="2025">2025</option>
              <option value="2024">2024</option>
            </select>
          </div>

        </div>

        <button type="submit" name="request_results">
          <i class="fa fa-paper-plane"></i>
          Request Results
        </button>

      </form>
    </div>

    <div class="comment-box" style="margin-top:30px;">
      <h3>Important Information</h3>
      <ul style="padding-left:20px; color:#555; line-height:1.7;">
        <li>You can only request one examination result at a time.</li>
        <li>Your request will be reviewed by the administrator.</li>
        <li>Your results will be available within 24 hours.</li>
        <li>College, course and level are pulled from your profile - update your profile if these are incorrect.</li>
      </ul>
    </div>

<?php require 'includes/footer.php'; ?>
