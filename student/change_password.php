<?php
session_start();

// Temporary user
$studentName = "Lonjezo Makhaula";

$message = "";
$messageType = ""; // "success" or "error"

if(isset($_POST['change_password'])){
    /* TODO (BACKEND):
       - Verify $_POST['current_password'] against the DB hash
       - Confirm $_POST['new_password'] === $_POST['confirm_password']
       - Hash and save the new password
       - Set $message / $messageType based on the actual result,
         e.g. on failure: $message = "Current password is incorrect.";
                          $messageType = "error"; */
    $message = "Password updated successfully! (Database connection coming later)";
    $messageType = "success";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Change Password — TEVETA Student Portal</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>

:root{
  --orange: #F15A22;
  --orange-dark: #d94d18;
  --bg: #f4f6f9;
  --text-muted: #777;
  --sidebar-width: 250px;
}

*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:'Segoe UI', Arial, sans-serif;
}

body{
  background: var(--bg);
}

.skip-link{
  position:absolute;
  left:-999px;
  top:0;
  background: var(--orange);
  color:white;
  padding:10px 16px;
  border-radius:0 0 8px 0;
  z-index:200;
}
.skip-link:focus{
  left:0;
}

/* Sidebar */

.sidebar{
  width: var(--sidebar-width);
  height:100vh;
  background:#fff;
  position:fixed;
  left:0;
  top:0;
  box-shadow:2px 0 10px rgba(0,0,0,.08);
  z-index:100;
  transition: transform .3s ease;
  overflow-y:auto;
}

.logo{
  text-align:center;
  padding:25px;
  border-bottom:1px solid #eee;
}

.logo img{
  width:90px;
  margin-bottom:10px;
}

.logo h2{
  color: var(--orange);
  font-size:22px;
}

.menu{
  margin-top:20px;
  list-style:none;
}

.menu a{
  display:flex;
  align-items:center;
  gap:14px;
  padding:16px 25px;
  text-decoration:none;
  color:#444;
  font-size:16px;
  transition: background .2s, color .2s;
}

.menu a i{
  width:20px;
  text-align:center;
}

.menu a:hover,
.menu a.active,
.menu a:focus-visible{
  background: var(--orange);
  color:#fff;
  outline:none;
}

/* Main */

.main{
  margin-left: var(--sidebar-width);
  width: calc(100% - var(--sidebar-width));
  transition: margin-left .3s ease;
}

/* Header */

.header{
  height:70px;
  background:#fff;
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:0 30px;
  box-shadow:0 2px 10px rgba(0,0,0,.05);
  position:sticky;
  top:0;
  z-index:50;
  gap:16px;
}

.header-left{
  display:flex;
  align-items:center;
  gap:16px;
  min-width:0;
}

.menu-toggle{
  display:none;
  background:none;
  border:none;
  font-size:22px;
  color: var(--orange);
  cursor:pointer;
  padding:6px;
  flex-shrink:0;
}

.header h1{
  color: var(--orange);
  font-size:20px;
  font-weight:700;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}

.user{
  font-weight:600;
  color:#444;
  display:flex;
  align-items:center;
  gap:8px;
  white-space:nowrap;
  flex-shrink:0;
}

/* Content */

.content{
  padding:30px;
}

/* Card */

.card{
  background:#fff;
  padding:35px;
  border-radius:10px;
  box-shadow:0 5px 15px rgba(0,0,0,.05);
  max-width:650px;
}

.card h2{
  color: var(--orange);
  margin-bottom:25px;
  font-size:20px;
}

.form-group{
  margin-bottom:20px;
}

label{
  display:block;
  margin-bottom:8px;
  font-weight:600;
  color:#444;
  font-size:14px;
}

.password-wrap{
  position:relative;
}

input{
  width:100%;
  padding:13px;
  border:1px solid #ccc;
  border-radius:8px;
  font-size:15px;
  outline:none;
  transition: border-color .2s, box-shadow .2s;
}

.password-wrap input{
  padding-right:48px;
}

input:focus{
  border-color: var(--orange);
  box-shadow: 0 0 0 3px rgba(241,90,34,0.15);
}

input.mismatch{
  border-color:#C0392B;
}

.toggle-password{
  position:absolute;
  right:10px;
  top:50%;
  transform: translateY(-50%);
  background:none;
  border:none;
  cursor:pointer;
  font-size:13px;
  color: var(--text-muted);
  font-weight:600;
  padding:6px 8px;
}
.toggle-password:hover{
  color: var(--orange);
}

.field-error{
  color:#C0392B;
  font-size:13px;
  margin-top:6px;
  display:none;
}
.field-error.show{
  display:block;
}

button[type="submit"]{
  width:100%;
  padding:14px;
  background: var(--orange);
  border:none;
  color:white;
  font-size:16px;
  font-weight:600;
  border-radius:8px;
  cursor:pointer;
  transition: background .2s;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:10px;
}

button[type="submit"]:hover{
  background: var(--orange-dark);
}
button[type="submit"]:focus-visible{
  outline:3px solid #FFD8C9;
  outline-offset:2px;
}

.message{
  padding:15px;
  border-radius:8px;
  margin-bottom:20px;
  font-size:14.5px;
}
.message.success{
  background:#d4edda;
  color:#155724;
}
.message.error{
  background:#fdeaea;
  color:#a93226;
}

.tips{
  margin-top:30px;
  background:#fff8f4;
  border-left:5px solid var(--orange);
  padding:20px;
  border-radius:8px;
  max-width:650px;
}

.tips h3{
  color: var(--orange);
  margin-bottom:15px;
  font-size:16px;
}

.tips ul{
  padding-left:20px;
  color:#555;
  line-height:1.7;
}

footer{
  text-align:center;
  margin-top:30px;
  color: var(--text-muted);
  font-size:13px;
}

/* Overlay for mobile sidebar */
.overlay{
  display:none;
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.4);
  z-index:90;
}
.overlay.show{
  display:block;
}

/* ===== MOBILE ===== */
@media (max-width: 900px){

  .sidebar{
    transform: translateX(-100%);
  }
  .sidebar.open{
    transform: translateX(0);
  }

  .main{
    margin-left:0;
    width:100%;
  }

  .menu-toggle{
    display:inline-block;
  }

  .header h1{
    font-size:16px;
  }
}

@media (max-width: 480px){
  .content{
    padding:18px;
  }
  .header{
    padding:0 16px;
  }
  .card,
  .tips{
    padding:20px;
  }
  .user span.user-name{
    display:none;
  }
}

@media (prefers-reduced-motion: reduce){
  *{ transition:none !important; }
}

</style>

</head>

<body>

<a href="#main-content" class="skip-link">Skip to content</a>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar" aria-label="Main navigation">

  <div class="logo">
    <img src="../images/teveta-logo.png" alt="TEVETA logo">
    <h2>Student Portal</h2>
  </div>

  <ul class="menu">
    <li><a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a></li>
    <li><a href="request_results.php"><i class="fa fa-file-alt"></i> Request Results</a></li>
    <li><a href="results.php"><i class="fa fa-list"></i> My Requests</a></li>
    <li><a href="profile.php"><i class="fa fa-user"></i> My Profile</a></li>
    <li><a href="#" class="active" aria-current="page"><i class="fa fa-lock"></i> Change Password</a></li>
    <li><a href="../logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
  </ul>

</nav>

<div class="overlay" id="overlay"></div>

<!-- Main -->
<div class="main">

  <div class="header">
    <div class="header-left">
      <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="sidebar">
        <i class="fa fa-bars"></i>
      </button>
      <h1>Change Password</h1>
    </div>

    <div class="user">
      <i class="fa fa-user-circle"></i>
      <span class="user-name"><?php echo htmlspecialchars($studentName); ?></span>
    </div>
  </div>

  <main class="content" id="main-content">

    <div class="card">

      <h2>Update Your Password</h2>

      <?php if ($message !== ""): ?>
        <div class="message <?php echo htmlspecialchars($messageType); ?>" role="alert">
          <?php echo htmlspecialchars($message); ?>
        </div>
      <?php endif; ?>

      <form method="POST" id="passwordForm" novalidate>

        <div class="form-group">
          <label for="current_password">Current Password</label>
          <div class="password-wrap">
            <input
              type="password"
              id="current_password"
              name="current_password"
              placeholder="Enter current password"
              autocomplete="current-password"
              required>
            <button type="button" class="toggle-password" data-target="current_password" aria-label="Show current password">Show</button>
          </div>
        </div>

        <div class="form-group">
          <label for="new_password">New Password</label>
          <div class="password-wrap">
            <input
              type="password"
              id="new_password"
              name="new_password"
              placeholder="Enter new password"
              autocomplete="new-password"
              minlength="8"
              required>
            <button type="button" class="toggle-password" data-target="new_password" aria-label="Show new password">Show</button>
          </div>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm New Password</label>
          <div class="password-wrap">
            <input
              type="password"
              id="confirm_password"
              name="confirm_password"
              placeholder="Confirm new password"
              autocomplete="new-password"
              minlength="8"
              required>
            <button type="button" class="toggle-password" data-target="confirm_password" aria-label="Show confirm password">Show</button>
          </div>
          <div class="field-error" id="mismatchError">Passwords do not match.</div>
        </div>

        <button type="submit" name="change_password">
          <i class="fa fa-key"></i>
          Update Password
        </button>

      </form>
    </div>

    <div class="tips">
      <h3>Password Tips</h3>
      <ul>
        <li>Use at least 8 characters.</li>
        <li>Include uppercase and lowercase letters.</li>
        <li>Include at least one number.</li>
        <li>Use a special character (e.g. @, #, $, %).</li>
        <li>Do not share your password with anyone.</li>
      </ul>
    </div>

    <footer>
      &copy; 2026 TEVETA Student Portal
    </footer>

  </main>

</div>

<script>
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const menuToggle = document.getElementById('menuToggle');

  function openSidebar(){
    sidebar.classList.add('open');
    overlay.classList.add('show');
    menuToggle.setAttribute('aria-expanded', 'true');
  }

  function closeSidebar(){
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
    menuToggle.setAttribute('aria-expanded', 'false');
  }

  menuToggle.addEventListener('click', () => {
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
  });

  overlay.addEventListener('click', closeSidebar);

  document.querySelectorAll('.menu a').forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 900) closeSidebar();
    });
  });

  // Show/Hide password toggles
  document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = document.getElementById(btn.dataset.target);
      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      btn.textContent = isHidden ? 'Hide' : 'Show';
    });
  });

  // Client-side check only - real validation must still happen in PHP,
  // since front-end checks can always be bypassed.
  const form = document.getElementById('passwordForm');
  const newPassword = document.getElementById('new_password');
  const confirmPassword = document.getElementById('confirm_password');
  const mismatchError = document.getElementById('mismatchError');

  form.addEventListener('submit', (e) => {
    if (newPassword.value !== confirmPassword.value) {
      e.preventDefault();
      confirmPassword.classList.add('mismatch');
      mismatchError.classList.add('show');
      confirmPassword.focus();
    }
  });

  confirmPassword.addEventListener('input', () => {
    confirmPassword.classList.remove('mismatch');
    mismatchError.classList.remove('show');
  });
</script>

</body>
</html>