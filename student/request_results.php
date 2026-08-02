<?php
session_start();

// Temporary student name
$studentName = "Lonjezo Makhaula";

$pageTitle  = "Request Examination Results";
$activeMenu = "request";
require 'includes/header.php';
?>

    <div class="card">

      <h2>Request Form</h2>

      <form method="POST" action="" novalidate>

        <div class="form-grid">

          <div class="form-group">
            <label for="college">College</label>
            <select id="college" name="college" required>
              <option value="" disabled selected>Select college</option>
              <option value="salima">Salima Technical College</option>
              <option value="lilongwe">Lilongwe Technical College</option>
              <option value="mzuzu">Mzuzu Technical College</option>
              <option value="zomba">Zomba Technical College</option>
            </select>
          </div>

          <div class="form-group">
            <label for="course">Course</label>
            <select id="course" name="course" required>
              <option value="" disabled selected>Select course</option>
              <option value="ict">ICT</option>
              <option value="electrical">Electrical Installation</option>
              <option value="plumbing">Plumbing</option>
              <option value="automotive">Automotive Mechanics</option>
              <option value="tailoring">Tailoring</option>
            </select>
          </div>

          <div class="form-group">
            <label for="level">Level</label>
            <select id="level" name="level" required>
              <option value="" disabled selected>Select level</option>
              <option value="1">Level 1</option>
              <option value="2">Level 2</option>
              <option value="3">Level 3</option>
              <option value="4">Level 4</option>
            </select>
          </div>

          <div class="form-group">
            <label for="exam_year">Examination Year</label>
            <select id="exam_year" name="exam_year" required>
              <option value="2026">2026</option>
              <option value="2025">2025</option>
              <option value="2024">2024</option>
              <option value="2023">2023</option>
              <option value="2022">2022</option>
              <option value="2021">2021</option>
            </select>
          </div>

        </div>

        <button type="submit">
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
        <li>You will be notified once your results are available.</li>
        <li>Ensure the selected college, course and level are correct.</li>
      </ul>
    </div>

<?php require 'includes/footer.php'; ?>
