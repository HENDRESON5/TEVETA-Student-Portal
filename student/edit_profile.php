<?php
require_once '../config/auth.php';
require_login();
require_role('Student');

$userId = $_SESSION['user_id'];
$studentId = $_SESSION['student_id'] ?? null;

$message = "";
$messageType = "";

// Current values
$fullname = $_SESSION['fullname'];
$collegeId = null;
$courseId = null;
$semesterId = null;

if ($studentId) {
    $stmt = mysqli_prepare($conn, "SELECT college_id, course_id, semester_id FROM students WHERE student_id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $studentId);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    if ($row) {
        $collegeId = $row['college_id'];
        $courseId = $row['course_id'];
        $semesterId = $row['semester_id'];
    }
}

if (isset($_POST['save_profile']) && $studentId) {
    $newFullname = trim($_POST['fullname'] ?? '');
    $newCollegeId = (int)($_POST['college'] ?? 0);
    $newCourseId = (int)($_POST['course'] ?? 0);
    $newSemesterId = (int)($_POST['level'] ?? 0);

    if ($newFullname === '' || $newCollegeId === 0 || $newCourseId === 0 || $newSemesterId === 0) {
        $message = "Please fill in all fields.";
        $messageType = "error";
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE users SET fullname = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $newFullname, $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $stmt2 = mysqli_prepare($conn, "UPDATE students SET college_id = ?, course_id = ?, semester_id = ? WHERE student_id = ?");
        mysqli_stmt_bind_param($stmt2, "iiii", $newCollegeId, $newCourseId, $newSemesterId, $studentId);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);

        $_SESSION['fullname'] = $newFullname;
        $fullname = $newFullname;
        $collegeId = $newCollegeId;
        $courseId = $newCourseId;
        $semesterId = $newSemesterId;

        $message = "Profile updated successfully!";
        $messageType = "success";
    }
}

// Dropdown data
$colleges = [];
$result = mysqli_query($conn, "SELECT college_id, college_name FROM colleges ORDER BY college_name ASC");
while ($row = mysqli_fetch_assoc($result)) { $colleges[] = $row; }

$courses = [];
$result = mysqli_query($conn, "SELECT course_id, course_name FROM courses ORDER BY course_name ASC");
while ($row = mysqli_fetch_assoc($result)) { $courses[] = $row; }

$levels = [];
$result = mysqli_query($conn, "SELECT semester_id, semester_name FROM semesters ORDER BY semester_id ASC");
while ($row = mysqli_fetch_assoc($result)) { $levels[] = $row; }

$pageTitle  = "Edit Profile";
$activeMenu = "profile";
require 'includes/header.php';
?>

    <div class="card">

      <h2>Edit Profile</h2>

      <?php if ($message !== ""): ?>
        <div class="message <?php echo htmlspecialchars($messageType); ?>" role="alert">
          <?php echo htmlspecialchars($message); ?>
        </div>
      <?php endif; ?>

      <form method="POST" novalidate>

        <div class="form-group">
          <label for="fullname">Full Name</label>
          <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" autocomplete="name" required>
        </div>

        <div class="form-grid">

          <div class="form-group">
            <label for="college">College</label>
            <select id="college" name="college" required>
              <?php foreach ($colleges as $college): ?>
                <option value="<?php echo (int)$college['college_id']; ?>" <?php echo $college['college_id'] == $collegeId ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($college['college_name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="course">Course</label>
            <select id="course" name="course" required>
              <?php foreach ($courses as $course): ?>
                <option value="<?php echo (int)$course['course_id']; ?>" <?php echo $course['course_id'] == $courseId ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($course['course_name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="level">Level</label>
            <select id="level" name="level" required>
              <?php foreach ($levels as $level): ?>
                <option value="<?php echo (int)$level['semester_id']; ?>" <?php echo $level['semester_id'] == $semesterId ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars(ucfirst($level['semester_name'])); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

        </div>

        <div class="buttons">
          <button class="btn edit" type="submit" name="save_profile">
            <i class="fa fa-save"></i>
            Save Changes
          </button>

          <a href="profile.php" class="btn back">
            <i class="fa fa-arrow-left"></i>
            Back to Profile
          </a>
        </div>

      </form>
    </div>

<?php require 'includes/footer.php'; ?>
