<?php
require_once '../config/auth.php';
require_login();
require_role('Admin');

$adminName = $_SESSION['fullname'];

function statusClass($status) {
  switch (strtolower($status)) {
    case "approved": return "status-approved";
    case "rejected": return "status-rejected";
    default:         return "status-pending";
  }
}

$isEditMode = isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['request_id']);
$currentRequest = null;

if ($isEditMode) {
    $requestId = (int)$_GET['request_id'];
    $stmt = mysqli_prepare($conn, "
        SELECT r.request_id, s.student_id, u.fullname AS student, col.college_name, co.course_name, sem.semester_name, r.status
        FROM result_requests r
        JOIN students s ON r.student_id = s.student_id
        JOIN users u ON s.user_id = u.id
        JOIN colleges col ON s.college_id = col.college_id
        JOIN courses co ON s.course_id = co.course_id
        JOIN semesters sem ON s.semester_id = sem.semester_id
        WHERE r.request_id = ?
    ");
    mysqli_stmt_bind_param($stmt, "i", $requestId);
    mysqli_stmt_execute($stmt);
    $currentRequest = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
}

$message = "";
$messageType = "";

if ($isEditMode && $currentRequest && isset($_POST['save_results'])) {

    $studentId = (int)$currentRequest['student_id'];
    $practicalScore = (int)$_POST['practical_score'];
    $practicalClass = $_POST['practical_classification'];
    $occupationalScore = (int)$_POST['occupational_score'];
    $occupationalClass = $_POST['occupational_classification'];
    $fundamentalScore = (int)$_POST['fundamental_score'];
    $fundamentalClass = $_POST['fundamental_classification'];
    $overallGrade = $_POST['grade'];
    $comment = trim($_POST['comment'] ?? '');
    $academicYear = date('Y');

    // Check if this student already has a results row - update it, otherwise insert new
    $checkStmt = mysqli_prepare($conn, "SELECT result_id FROM results WHERE student_id = ? LIMIT 1");
    mysqli_stmt_bind_param($checkStmt, "i", $studentId);
    mysqli_stmt_execute($checkStmt);
    $existing = mysqli_fetch_assoc(mysqli_stmt_get_result($checkStmt));
    mysqli_stmt_close($checkStmt);

    if ($existing) {
        $stmt = mysqli_prepare($conn, "
            UPDATE results SET
                practical_score = ?, practical_classification = ?,
                occupational_score = ?, occupational_classification = ?,
                fundamental_score = ?, fundamental_classification = ?,
                overall_classification = ?, admin_comment = ?, academic_year = ?
            WHERE result_id = ?
        ");
        $resultId = $existing['result_id'];
        mysqli_stmt_bind_param($stmt, "isisissssi",
            $practicalScore, $practicalClass,
            $occupationalScore, $occupationalClass,
            $fundamentalScore, $fundamentalClass,
            $overallGrade, $comment, $academicYear, $resultId
        );
    } else {
        $stmt = mysqli_prepare($conn, "
            INSERT INTO results
                (student_id, practical_score, practical_classification,
                 occupational_score, occupational_classification,
                 fundamental_score, fundamental_classification,
                 overall_classification, admin_comment, academic_year)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        mysqli_stmt_bind_param($stmt, "iisisissss",
            $studentId, $practicalScore, $practicalClass,
            $occupationalScore, $occupationalClass,
            $fundamentalScore, $fundamentalClass,
            $overallGrade, $comment, $academicYear
        );
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Mark the request as approved now that results are entered
    $updateReq = mysqli_prepare($conn, "UPDATE result_requests SET status = 'Approved' WHERE request_id = ?");
    mysqli_stmt_bind_param($updateReq, "i", $requestId);
    mysqli_stmt_execute($updateReq);
    mysqli_stmt_close($updateReq);

    $message = "Results saved successfully.";
    $messageType = "success";
    $currentRequest['status'] = 'Approved'; // reflect immediately on this page load
}

$requests = [];
if (!$isEditMode) {
    $result = mysqli_query($conn, "
        SELECT r.request_id, u.fullname AS student, col.college_name, co.course_name, sem.semester_name, r.status
        FROM result_requests r
        JOIN students s ON r.student_id = s.student_id
        JOIN users u ON s.user_id = u.id
        JOIN colleges col ON s.college_id = col.college_id
        JOIN courses co ON s.course_id = co.course_id
        JOIN semesters sem ON s.semester_id = sem.semester_id
        ORDER BY r.request_date DESC
    ");
    while ($row = mysqli_fetch_assoc($result)) {
        $requests[] = [
            "id"      => $row["request_id"],
            "student" => $row["student"],
            "college" => $row["college_name"],
            "course"  => $row["course_name"],
            "level"   => ucfirst($row["semester_name"]),
            "status"  => $row["status"],
        ];
    }
}

$pageTitle  = $isEditMode ? "Edit Results" : "Result Requests";
$activeMenu = "requests";
require 'includes/header.php';
?>

<?php if ($isEditMode): ?>

  <?php if ($currentRequest === null): ?>

    <div class="card">
      <p>That result request could not be found.</p>
      <div class="buttons">
        <a href="result_requests.php" class="btn secondary"><i class="fa fa-arrow-left"></i> Back to Requests</a>
      </div>
    </div>

  <?php else: ?>

    <div class="card">

      <h2>Student Information</h2>

      <div class="info-grid">
        <div class="info-box">
          <label>Student Name</label>
          <p><?php echo htmlspecialchars($currentRequest["student"]); ?></p>
        </div>
        <div class="info-box">
          <label>College</label>
          <p><?php echo htmlspecialchars($currentRequest["college_name"]); ?></p>
        </div>
        <div class="info-box">
          <label>Course</label>
          <p><?php echo htmlspecialchars($currentRequest["course_name"]); ?></p>
        </div>
        <div class="info-box">
          <label>Level</label>
          <p><?php echo htmlspecialchars(ucfirst($currentRequest["semester_name"])); ?></p>
        </div>
      </div>

      <?php if ($message !== ""): ?>
        <div class="message <?php echo htmlspecialchars($messageType); ?>" role="alert">
          <?php echo htmlspecialchars($message); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="result_requests.php?action=edit&request_id=<?php echo (int)$currentRequest['request_id']; ?>" novalidate>

        <div class="paper-grid">

          <div class="paper-field">
            <h4><i class="fa fa-screwdriver-wrench"></i> Practical Paper</h4>
            <div class="form-group">
              <label for="practical_score">Score</label>
              <input type="number" id="practical_score" name="practical_score" min="0" max="100" placeholder="e.g. 82" required>
            </div>
            <div class="form-group">
              <label for="practical_classification">Classification</label>
              <select id="practical_classification" name="practical_classification" required>
                <option value="" disabled selected>Select</option>
                <option value="Distinction">Distinction</option>
                <option value="Credit">Credit</option>
                <option value="Pass">Pass</option>
                <option value="Fail">Fail</option>
              </select>
            </div>
          </div>

          <div class="paper-field">
            <h4><i class="fa fa-briefcase"></i> Occupational Paper</h4>
            <div class="form-group">
              <label for="occupational_score">Score</label>
              <input type="number" id="occupational_score" name="occupational_score" min="0" max="100" placeholder="e.g. 65" required>
            </div>
            <div class="form-group">
              <label for="occupational_classification">Classification</label>
              <select id="occupational_classification" name="occupational_classification" required>
                <option value="" disabled selected>Select</option>
                <option value="Distinction">Distinction</option>
                <option value="Credit">Credit</option>
                <option value="Pass">Pass</option>
                <option value="Fail">Fail</option>
              </select>
            </div>
          </div>

          <div class="paper-field">
            <h4><i class="fa fa-book"></i> Fundamental Paper</h4>
            <div class="form-group">
              <label for="fundamental_score">Score</label>
              <input type="number" id="fundamental_score" name="fundamental_score" min="0" max="100" placeholder="e.g. 70" required>
            </div>
            <div class="form-group">
              <label for="fundamental_classification">Classification</label>
              <select id="fundamental_classification" name="fundamental_classification" required>
                <option value="" disabled selected>Select</option>
                <option value="Distinction">Distinction</option>
                <option value="Credit">Credit</option>
                <option value="Pass">Pass</option>
                <option value="Fail">Fail</option>
              </select>
            </div>
          </div>

        </div>

        <div class="form-group" style="margin-top:22px;">
          <label for="grade">Overall Grade</label>
          <select id="grade" name="grade" required>
            <option value="" disabled selected>Select overall grade</option>
            <option value="Distinction">Distinction</option>
            <option value="Credit">Credit</option>
            <option value="Pass">Pass</option>
            <option value="Fail">Fail</option>
          </select>
        </div>

        <div class="form-group">
          <label for="comment">Administrator Comment</label>
          <textarea id="comment" name="comment" placeholder="Optional comment for the student"></textarea>
        </div>

        <div class="buttons">
          <button type="submit" name="save_results" class="primary">
            <i class="fa fa-save"></i>
            Save Results
          </button>
          <a href="result_requests.php" class="btn secondary">
            <i class="fa fa-arrow-left"></i>
            Back to Requests
          </a>
        </div>

      </form>
    </div>

  <?php endif; ?>

<?php else: ?>

  <div class="search-card">
    <div class="search-wrap">
      <i class="fa fa-search"></i>
      <input
        type="text"
        id="searchInput"
        placeholder="Search by student, college or course"
        aria-label="Search result requests">
    </div>

    <div class="total">
      Total Requests: <?php echo (int)count($requests); ?>
    </div>
  </div>

  <div class="table-card">

    <h2>Student Result Requests</h2>

    <table id="requestsTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Student</th>
          <th>College</th>
          <th>Course</th>
          <th>Level</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($requests)): ?>
          <tr><td colspan="7" class="no-results">No result requests yet.</td></tr>
        <?php else: ?>
          <?php foreach ($requests as $req): ?>
            <tr>
              <td><?php echo str_pad($req["id"], 3, "0", STR_PAD_LEFT); ?></td>
              <td class="col-student"><?php echo htmlspecialchars($req["student"]); ?></td>
              <td class="col-college"><?php echo htmlspecialchars($req["college"]); ?></td>
              <td class="col-course"><?php echo htmlspecialchars($req["course"]); ?></td>
              <td><?php echo htmlspecialchars($req["level"]); ?></td>
              <td><span class="status <?php echo statusClass($req["status"]); ?>"><?php echo htmlspecialchars(ucfirst($req["status"])); ?></span></td>
              <td>
                <a href="result_requests.php?action=edit&amp;request_id=<?php echo (int)$req['id']; ?>" class="view">
                  <?php echo strtolower($req['status']) === 'pending' ? 'Enter Results' : 'Edit Results'; ?>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <p class="no-results" id="noMatchMessage" style="display:none;">No requests match your search.</p>

  </div>

<?php endif; ?>

<?php
if (!$isEditMode) {
  $pageScript = <<<'JS'
  const searchInput = document.getElementById('searchInput');
  const tableRows = Array.from(document.querySelectorAll('#requestsTable tbody tr'));
  const noMatchMessage = document.getElementById('noMatchMessage');

  if (searchInput) {
    searchInput.addEventListener('input', () => {
      const term = searchInput.value.trim().toLowerCase();
      let visibleCount = 0;

      tableRows.forEach(row => {
        const student = row.querySelector('.col-student')?.textContent.toLowerCase() || '';
        const college = row.querySelector('.col-college')?.textContent.toLowerCase() || '';
        const course = row.querySelector('.col-course')?.textContent.toLowerCase() || '';

        const matches = student.includes(term) || college.includes(term) || course.includes(term);
        row.style.display = matches ? '' : 'none';
        if (matches) visibleCount++;
      });

      if (noMatchMessage) noMatchMessage.style.display = visibleCount === 0 ? 'block' : 'none';
    });
  }
JS;
}
require 'includes/footer.php';
?>
