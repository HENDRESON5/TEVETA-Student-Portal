<?php
session_start();

// Temporary admin data
$adminName = "System Administrator";

/* TODO (BACKEND): replace with a real query
   SELECT full_name, college, course, level
   FROM students
   ORDER BY full_name ASC; */
$students = [
  ["name" => "Lonjezo Makhaula", "college" => "Salima Technical College",   "course" => "ICT",                    "level" => "Level 2"],
  ["name" => "John Banda",       "college" => "Mzuzu Technical College",    "course" => "Electrical Installation", "level" => "Level 3"],
  ["name" => "Mary Phiri",       "college" => "Lilongwe Technical College", "course" => "Plumbing",                "level" => "Level 1"],
  ["name" => "Peter Mbewe",      "college" => "Zomba Technical College",    "course" => "Tailoring",               "level" => "Level 2"],
];

$totalStudents = count($students);

$pageTitle  = "Registered Students";
$activeMenu = "students";
require 'includes/header.php';
?>

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
          </tr>
        </thead>
        <tbody>
          <?php if (empty($students)): ?>
            <tr><td colspan="4" class="no-results">No students registered yet.</td></tr>
          <?php else: ?>
            <?php foreach ($students as $student): ?>
              <tr>
                <td class="col-name"><?php echo htmlspecialchars($student["name"]); ?></td>
                <td class="col-college"><?php echo htmlspecialchars($student["college"]); ?></td>
                <td class="col-course"><?php echo htmlspecialchars($student["course"]); ?></td>
                <td><?php echo htmlspecialchars($student["level"]); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>

      <p class="no-results" id="noMatchMessage" style="display:none;">No students match your search.</p>

    </div>

<?php
$pageScript = <<<'JS'
  // Live client-side search filter across name, college and course.
  // NOTE: only filters rows already on the page. If your friend adds
  // pagination later, this should become a real DB search instead.
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
JS;
require 'includes/footer.php';
?>
