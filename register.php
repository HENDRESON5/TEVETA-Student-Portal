<?php
require_once 'config/database.php';

$message = "";
$messageType = ""; // "success" or "error"

// ------------------------------------------------------------
// Pull colleges, courses and levels straight from the database


$colleges = [];
$result = mysqli_query($conn, "SELECT college_id, college_name FROM colleges ORDER BY college_name ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $colleges[] = $row;
}

$courses = [];
$result = mysqli_query($conn, "SELECT course_id, course_name FROM courses ORDER BY course_name ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row;
}

$levels = [];
$result = mysqli_query($conn, "SELECT semester_id, semester_name FROM semesters ORDER BY semester_id ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $levels[] = $row;
}

// ------------------------------------------------------------
// Handle form submission
// ------------------------------------------------------------

if (isset($_POST['register'])) {

    $fullname   = trim($_POST['fullname'] ?? '');
    $collegeId  = (int)($_POST['college'] ?? 0);
    $courseId   = (int)($_POST['course'] ?? 0);
    $semesterId = (int)($_POST['level'] ?? 0);
    $username   = trim($_POST['username'] ?? '');
    $password   = $_POST['password'] ?? '';
    $confirm    = $_POST['confirm'] ?? '';

    if ($fullname === '' || $collegeId === 0 || $courseId === 0 || $semesterId === 0 || $username === '' || $password === '') {
        $message = "Please fill in all fields.";
        $messageType = "error";
    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters.";
        $messageType = "error";
    } elseif ($password !== $confirm) {
        $message = "Passwords do not match!";
        $messageType = "error";
    } else {

        // Check the username isn't already taken
        $checkStmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? LIMIT 1");
        mysqli_stmt_bind_param($checkStmt, "s", $username);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $message = "That username is already taken. Please choose another.";
            $messageType = "error";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            mysqli_begin_transaction($conn);

            try {
                // 1. Insert into users
                $userStmt = mysqli_prepare($conn, "INSERT INTO users (fullname, username, password, role, status) VALUES (?, ?, ?, 'Student', 'Active')");
                mysqli_stmt_bind_param($userStmt, "sss", $fullname, $username, $hashedPassword);
                mysqli_stmt_execute($userStmt);
                $newUserId = mysqli_insert_id($conn);

                // 2. Insert into students, linked via user_id
                $studentStmt = mysqli_prepare($conn, "INSERT INTO students (user_id, course_id, college_id, semester_id) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($studentStmt, "iiii", $newUserId, $courseId, $collegeId, $semesterId);
                mysqli_stmt_execute($studentStmt);

                mysqli_commit($conn);

                $message = "Registration successful! You can now log in.";
                $messageType = "success";

            } catch (mysqli_sql_exception $e) {
                mysqli_rollback($conn);
                $message = "Something went wrong while creating your account. Please try again.";
                $messageType = "error";
                // TODO (BACKEND): log $e->getMessage() somewhere for debugging,
                // don't show raw DB errors to the user in production.
            }
        }

        mysqli_stmt_close($checkStmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register | Student Portal</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:linear-gradient(135deg,#F15A22,#ffffff);
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    padding:30px;
}

.register-box{
    width:450px;
    max-width:100%;
    background:#fff;
    padding:35px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
}

.logo{
    display:block;
    margin:auto;
    width:90px;
}

h2{
    text-align:center;
    color:#F15A22;
    margin:10px 0;
}

.subtitle{
    text-align:center;
    color:#777;
    margin-bottom:20px;
}

.message{
    padding:12px;
    border-radius:8px;
    text-align:center;
    margin-bottom:15px;
    font-size:14px;
}
.message.success{
    background:#d4edda;
    color:#155724;
}
.message.error{
    background:#fdeaea;
    color:#a93226;
}

label{
    display:block;
    font-size:13px;
    font-weight:600;
    color:#3B3E42;
    margin:10px 0 6px;
}

input,select{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:15px;
    outline:none;
    transition:border-color .2s, box-shadow .2s;
}

input:focus,
select:focus{
    border-color:#F15A22;
    box-shadow: 0 0 0 3px rgba(241,90,34,0.15);
}

button{
    width:100%;
    padding:13px;
    background:#F15A22;
    color:#fff;
    border:none;
    border-radius:8px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    margin-top:18px;
    transition:background .2s;
}

button:hover{
    background:#d94d18;
}

.bottom{
    text-align:center;
    margin-top:20px;
    font-size:14px;
    color:#777;
}

.bottom a{
    color:#F15A22;
    text-decoration:none;
    font-weight:bold;
}

.bottom a:hover{
    text-decoration:underline;
}

</style>

</head>
<body>

<div class="register-box">

<img src="images/teveta-logo.png" class="logo" alt="TEVETA logo">

<h2>Create Account</h2>
<p class="subtitle">TEVETA Student Portal</p>

<?php if ($message !== ""): ?>
  <p class="message <?php echo htmlspecialchars($messageType); ?>"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<?php if ($messageType !== "success"): ?>

<form method="POST" novalidate>

  <label for="fullname">Full Name</label>
  <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required>

  <label for="college">College</label>
  <select id="college" name="college" required>
    <option value="">Select College</option>
    <?php foreach ($colleges as $college): ?>
      <option value="<?php echo (int)$college['college_id']; ?>"><?php echo htmlspecialchars($college['college_name']); ?></option>
    <?php endforeach; ?>
  </select>

  <label for="course">Course</label>
  <select id="course" name="course" required>
    <option value="">Select Course</option>
    <?php foreach ($courses as $course): ?>
      <option value="<?php echo (int)$course['course_id']; ?>"><?php echo htmlspecialchars($course['course_name']); ?></option>
    <?php endforeach; ?>
  </select>

  <label for="level">Level</label>
  <select id="level" name="level" required>
    <option value="">Select Level</option>
    <?php foreach ($levels as $level): ?>
      <option value="<?php echo (int)$level['semester_id']; ?>"><?php echo htmlspecialchars(ucfirst($level['semester_name'])); ?></option>
    <?php endforeach; ?>
  </select>

  <label for="username">Username</label>
  <input type="text" id="username" name="username" placeholder="Choose a username" autocomplete="username" required>

  <label for="password">Password</label>
  <input type="password" id="password" name="password" placeholder="Create a password" autocomplete="new-password" minlength="8" required>

  <label for="confirm">Confirm Password</label>
  <input type="password" id="confirm" name="confirm" placeholder="Re-enter your password" autocomplete="new-password" minlength="8" required>

  <button type="submit" name="register">Create Account</button>

</form>

<?php endif; ?>

<div class="bottom">
  Already have an account? <a href="login.php">Login</a>
</div>

</div>

</body>
</html>