<?php
session_start();

// Temporary admin data
$adminName = "System Administrator";

/* TODO (BACKEND): replace with a real query, most recent first
   SELECT r.id, s.id AS student_id, s.name AS student, s.college, s.course, s.level, r.exam_year, r.status
   FROM result_requests r JOIN students s ON r.student_id = s.id
   ORDER BY r.created_at DESC; */
$requests = [
  ["id" => 1, "student" => "Lonjezo Makhaula", "college" => "Salima Technical College",   "course" => "ICT",                     "level" => "Level 2", "year" => 2026, "status" => "pending"],
  ["id" => 2, "student" => "John Banda",       "college" => "Mzuzu Technical College",    "course" => "Electrical Installation",  "level" => "Level 3", "year" => 2026, "status" => "approved"],
  ["id" => 3, "student" => "Mary Phiri",       "college" => "Lilongwe Technical College", "course" => "Plumbing",                 "level" => "Level 1", "year" => 2025, "status" => "rejected"],
  ["id" => 4, "student" => "Peter Mbewe",      "college" => "Zomba Technical College",    "course" => "Tailoring",                "level" => "Level 2", "year" => 2026, "status" => "pending"],
];

function statusClass($status) {
  switch (strtolower($status)) {
    case "approved": return "status-approved";
    case "rejected": return "status-rejected";
    default:         return "status-pending";
  }
}

// ------------------------------------------------------------
// Are we viewing the list, or editing one specific request?
// ?action=edit&request_id=3 switches into edit mode.
// ------------------------------------------------------------
$isEditMode = isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['request_id']);
$currentRequest = null;

if ($isEditMode) {
  $requestId = (int)$_GET['request_id'];
  foreach ($requests as $req) {
    if ($req['id'] === $requestId) {
      $currentRequest = $req;
      break;
    }
  }
}

$message = "";
$messageType = "";

if ($isEditMode && isset($_POST['save_results'])) {
  /* TODO (BACKEND):
     - Validate practical/occupational/fundamental scores + classifications
       and the overall grade use the allowed values ("Pass"|"Credit"|"Distinction"|"Fail")
     - Save everything to the results table for this request's student
     - Update this request's status (e.g. to "approved") once results are entered
     - Save $_POST['comment'] as the admin_comment
     - Set $message / $messageType based on the actual result */
  $message = "Results saved successfully! (Database integration coming later)";
  $messageType = "success";
}

/* TODO (BACKEND): if editing, pre-fill the form below with this student's
   EXISTING scores/classifications if they've been entered before (e.g. re-editing). */

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
          <p><?php echo htmlspecialchars($currentRequest["college"]); ?></p>
        </div>
        <div class="info-box">
          <label>Course</label>
          <p><?php echo htmlspecialchars($currentRequest["course"]); ?></p>
        </div>
        <div class="info-box">
          <label>Level</label>
          <p><?php echo htmlspecialchars($currentRequest["level"]); ?></p>
        </div>
      </div>

      <?php if ($message !== ""): ?>
        <div class="message <?php echo htmlspecialchars($messageType); ?>" role="alert">
          <?php echo htmlspecialchars($message); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="result_requests.php?action=edit&request_id=<?php echo (int)$currentRequest['id']; ?>" novalidate>

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
          <th>Year</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($requests)): ?>
          <tr><td colspan="8" class="no-results">No result requests yet.</td></tr>
        <?php else: ?>
          <?php foreach ($requests as $req): ?>
            <tr>
              <td><?php echo str_pad($req["id"], 3, "0", STR_PAD_LEFT); ?></td>
              <td class="col-student"><?php echo htmlspecialchars($req["student"]); ?></td>
              <td class="col-college"><?php echo htmlspecialchars($req["college"]); ?></td>
              <td class="col-course"><?php echo htmlspecialchars($req["course"]); ?></td>
              <td><?php echo htmlspecialchars($req["level"]); ?></td>
              <td><?php echo htmlspecialchars($req["year"]); ?></td>
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
  // Live client-side search filter across student, college and course.
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
