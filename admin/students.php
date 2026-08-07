<?php
require_once '../config/auth.php';
require_login();
require_role('Admin');

$adminName = $_SESSION['fullname'];

$message = "";
$messageType = "";

if (isset($_POST['delete_student'])) {
    $userIdToDelete = (int)$_POST['user_id'];
    $studentIdToDelete = (int)$_POST['student_id'];

    // Delete in dependency order first, since MyISAM doesn't cascade deletes.
    $stmt = mysqli_prepare($conn, "DELETE FROM results WHERE student_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $studentIdToDelete);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "DELETE FROM result_requests WHERE student_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $studentIdToDelete);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "DELETE FROM notifications WHERE student_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $studentIdToDelete);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "DELETE FROM password_reset_requests WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userIdToDelete);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "DELETE FROM students WHERE student_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $studentIdToDelete);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userIdToDelete);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $message = "Student account deleted successfully.";
    $messageType = "success";
}

$students = [];
$result = mysqli_query($conn, "
    SELECT s.student_id, u.id AS user_id, u.fullname, col.college_name, co.course_name, sem.semester_name
    FROM students s
    JOIN users u ON s.user_id = u.id
    JOIN colleges col ON s.college_id = col.college_id
    JOIN courses co ON s.course_id = co.course_id
    JOIN semesters sem ON s.semester_id = sem.semester_id
    ORDER BY u.fullname ASC
");
while ($row = mysqli_fetch_assoc($result)) {
    $students[] = [
        "student_id" => $row["student_id"],
        "user_id"    => $row["user_id"],
        "name"       => $row["fullname"],
        "college"    => $row["college_name"],
        "course"     => $row["course_name"],
        "level"      => ucfirst($row["semester_name"]),
    ];
}

$totalStudents = count($students);

$pageTitle  = "Registered Students";
$activeMenu = "students";
require 'includes/header.php';
?>

    <?php if ($message !== ""): ?>
      <div class="message <?php echo htmlspecialchars($messageType); ?>" role="alert" style="margin-bottom:20px;">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <div class="search-card">
      <div class="search-wrap">
        <i class="fa fa-search"></i>
        <input
          type="text"
          id="searchInput"
          placeholder="Search by name, college or course"
          aria-label="Search registered students">
      </div>

      <div class="total">
        Total Students: <?php echo (int)$totalStudents; ?>
      </div>
    </div>

    <div class="table-card">

      <h2>Students Who Created Accounts</h2>

      <table id="studentsTable">
        <thead>
          <tr>
            <th>Full Name</th>
            <th>College</th>
            <th>Course</th>
            <th>Level</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($students)): ?>
            <tr><td colspan="5" class="no-results">No students registered yet.</td></tr>
          <?php else: ?>
            <?php foreach ($students as $student): ?>
              <tr>
                <td class="col-name"><?php echo htmlspecialchars($student["name"]); ?></td>
                <td class="col-college"><?php echo htmlspecialchars($student["college"]); ?></td>
                <td class="col-course"><?php echo htmlspecialchars($student["course"]); ?></td>
                <td><?php echo htmlspecialchars($student["level"]); ?></td>
                <td>
                  <form method="POST" class="delete-student-form" style="display:inline;">
                    <input type="hidden" name="student_id" value="<?php echo (int)$student['student_id']; ?>">
                    <input type="hidden" name="user_id" value="<?php echo (int)$student['user_id']; ?>">
                    <button type="submit" name="delete_student" class="view" style="background:#C0392B; border:none; cursor:pointer;">
                      <i class="fa fa-trash"></i> Delete
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>

      <p class="no-results" id="noMatchMessage" style="display:none;">No students match your search.</p>

    </div>

<?php
$pageScript = <<<'JS'
  const searchInput = document.getElementById('searchInput');
  const tableRows = Array.from(document.querySelectorAll('#studentsTable tbody tr'));
  const noMatchMessage = document.getElementById('noMatchMessage');

  if (searchInput) {
    searchInput.addEventListener('input', () => {
      const term = searchInput.value.trim().toLowerCase();
      let visibleCount = 0;

      tableRows.forEach(row => {
        const name = row.querySelector('.col-name')?.textContent.toLowerCase() || '';
        const college = row.querySelector('.col-college')?.textContent.toLowerCase() || '';
        const course = row.querySelector('.col-course')?.textContent.toLowerCase() || '';

        const matches = name.includes(term) || college.includes(term) || course.includes(term);
        row.style.display = matches ? '' : 'none';
        if (matches) visibleCount++;
      });

      if (noMatchMessage) noMatchMessage.style.display = visibleCount === 0 ? 'block' : 'none';
    });
  }

  // Confirm before deleting - this is permanent.
  document.querySelectorAll('.delete-student-form').forEach(form => {
    form.addEventListener('submit', (e) => {
      const name = form.closest('tr').querySelector('.col-name')?.textContent.trim() || 'this student';
      if (!confirm('Delete ' + name + '\\'s account permanently? This cannot be undone.')) {
        e.preventDefault();
      }
    });
  });
JS;
require 'includes/footer.php';
?>
