<?php
require_once 'config/database.php';

$message = "";
$messageType = "";

if (isset($_POST['send_reset'])) {
    $username = trim($_POST['username'] ?? '');
    $fullname = trim($_POST['full_name'] ?? '');

    if ($username === '' || $fullname === '') {
        $message = "Please fill in both fields.";
        $messageType = "error";
    } else {
        // Basic identity check: username must exist AND roughly match the
        // name on file (case-insensitive). Not bulletproof, but there's no
        // email/OTP available to verify with in this system.
        $stmt = mysqli_prepare($conn, "SELECT id, fullname FROM users WHERE username = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if ($user && strcasecmp(trim($user['fullname']), $fullname) === 0) {
            $checkStmt = mysqli_prepare($conn, "SELECT request_id FROM password_reset_requests WHERE user_id = ? AND status = 'Pending' LIMIT 1");
            mysqli_stmt_bind_param($checkStmt, "i", $user['id']);
            mysqli_stmt_execute($checkStmt);
            mysqli_stmt_store_result($checkStmt);

            if (mysqli_stmt_num_rows($checkStmt) === 0) {
                $insertStmt = mysqli_prepare($conn, "INSERT INTO password_reset_requests (user_id) VALUES (?)");
                mysqli_stmt_bind_param($insertStmt, "i", $user['id']);
                mysqli_stmt_execute($insertStmt);
                mysqli_stmt_close($insertStmt);
            }
            mysqli_stmt_close($checkStmt);
        }

        // Show the SAME message whether or not the details matched a real
        // account - prevents this form being used to probe which usernames exist.
        $message = "Your request has been submitted. Please wait for an administrator to reset your password within 24 hours.Your new password will be 1-8.";
        $messageType = "success";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password — TEVETA Student Portal</title>

<style>
:root{
  --orange: #F15A22;
  --orange-dark: #d94d18;
  --gray-text: #6F7378;
  --border: #BFC3C7;
  --success-bg: #d4edda;
  --success-text: #155724;
  --error-bg: #fdeaea;
  --error-text: #a93226;
}

*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family: "Segoe UI", Arial, sans-serif;
}

body{
  background: linear-gradient(135deg, var(--orange), #ffffff);
  min-height: 100vh;
  display:flex;
  justify-content:center;
  align-items:center;
  padding: 20px;
}

.reset-box{
  width: 400px;
  max-width: 100%;
  background:#fff;
  border-radius:15px;
  padding: 35px;
  box-shadow: 0 15px 35px rgba(0,0,0,.15);
  text-align:center;
}

.logo{
  width:120px;
  margin-bottom:10px;
}

h1{
  color: var(--orange);
  font-size: 1.5rem;
  margin-bottom:5px;
}

p.subtitle{
  color: var(--gray-text);
  margin-bottom:25px;
  font-size:14px;
  line-height:1.5;
}

.message{
  text-align:left;
  border-radius:8px;
  padding:12px 14px;
  font-size:13.5px;
  margin-bottom:20px;
}
.message.success{
  background: var(--success-bg);
  color: var(--success-text);
}
.message.error{
  background: var(--error-bg);
  color: var(--error-text);
}

form{
  text-align:left;
}

label{
  display:block;
  font-size:13px;
  font-weight:600;
  color:#3B3E42;
  margin-bottom:6px;
}

.input-box{
  width:100%;
  padding:12px;
  border:1px solid var(--border);
  border-radius:8px;
  outline:none;
  font-size:15px;
  transition: border-color .2s, box-shadow .2s;
}

.input-box:focus{
  border-color: var(--orange);
  box-shadow: 0 0 0 3px rgba(241,90,34,0.15);
}

button[type="submit"]{
  width:100%;
  padding:12px;
  margin-top:20px;
  background: var(--orange);
  color:#fff;
  border:none;
  border-radius:8px;
  font-size:16px;
  font-weight:600;
  cursor:pointer;
  transition: background .2s, transform .05s;
}

button[type="submit"]:hover{
  background: var(--orange-dark);
}
button[type="submit"]:active{
  transform: scale(0.99);
}
button[type="submit"]:focus-visible{
  outline: 3px solid #FFD8C9;
  outline-offset: 2px;
}

.links{
  margin-top:20px;
  font-size:14px;
  text-align:center;
  color: var(--gray-text);
}

.links a{
  color: var(--orange);
  text-decoration:none;
  font-weight:600;
}
.links a:hover,
.links a:focus-visible{
  text-decoration:underline;
}

@media (prefers-reduced-motion: reduce){
  *{ transition: none !important; }
}
</style>

</head>
<body>

<div class="reset-box">

  <img src="images/teveta-logo.png" class="logo" alt="TEVETA logo">

  <h1>Forgot Password</h1>
  <p class="subtitle">
    Since this portal doesn't use email, submit your details below and
    an administrator will verify your identity and reset your password,
    it will be done within 24 hours and your the new password will be 1-8.
  </p>

  <?php if ($message !== ""): ?>
    <div class="message <?php echo htmlspecialchars($messageType); ?>" role="alert">
      <?php echo htmlspecialchars($message); ?>
    </div>
  <?php else: ?>

  <form action="" method="POST" novalidate>

    <label for="username">Username</label>
    <input
      type="text"
      id="username"
      name="username"
      class="input-box"
      placeholder="Enter your username"
      autocomplete="username"
      required
    >

    <label for="full_name" style="margin-top:14px;">Full Name (as registered)</label>
    <input
      type="text"
      id="full_name"
      name="full_name"
      class="input-box"
      placeholder="Enter your full name"
      autocomplete="name"
      required
    >

    <button type="submit" name="send_reset">Submit Reset Request</button>

  </form>

  <?php endif; ?>

  <div class="links">
    <a href="login.php">Back to Login</a>
  </div>

</div>

</body>
</html>
