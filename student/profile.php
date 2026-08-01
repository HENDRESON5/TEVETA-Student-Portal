<?php
session_start();

// Temporary student data
$studentName = "Lonjezo Makhaula";
$username = "lonjezo";
$college = "Salima Technical College";
$course = "ICT";
$level = "Level 2";

// TODO (BACKEND): pull account status from DB (e.g. "Active" / "Suspended")
$accountStatus = "Active";

// Small helper for the avatar - shows the student's initials
function initials($name) {
  $parts = preg_split('/\s+/', trim($name));
  $letters = array_map(fn($p) => strtoupper(substr($p, 0, 1)), array_slice($parts, 0, 2));
  return implode('', $letters);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Profile — TEVETA Student Portal</title>

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
  padding:25px;
  text-align:center;
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
  color:white;
  outline:none;
}

/* Main */

.main{
  margin-left: var(--sidebar-width);
  width: calc(100% - var(--sidebar-width));
  transition: margin-left .3s ease;
}

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

.profile-card{
  background:#fff;
  padding:35px;
  border-radius:10px;
  box-shadow:0 5px 15px rgba(0,0,0,.05);
}

.profile-top{
  text-align:center;
  margin-bottom:30px;
}

.avatar{
  width:110px;
  height:110px;
  border-radius:50%;
  background: var(--orange);
  color:white;
  display:flex;
  justify-content:center;
  align-items:center;
  font-size:36px;
  font-weight:700;
  margin:auto;
  margin-bottom:15px;
  letter-spacing:1px;
}

.profile-top h2{
  color:#333;
  margin-bottom:5px;
  font-size:22px;
}

.profile-top p{
  color: var(--text-muted);
}

.info{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:20px;
  margin-top:20px;
}

.box{
  background:#fafafa;
  padding:18px;
  border-radius:8px;
  border-left:4px solid var(--orange);
}

.box label{
  font-weight:bold;
  color:#666;
  display:block;
  margin-bottom:8px;
  font-size:13px;
  text-transform:uppercase;
  letter-spacing:.4px;
}

.box p{
  font-size:16px;
  color:#333;
  word-break:break-word;
}

.status-badge{
  display:inline-block;
  padding:4px 12px;
  border-radius:20px;
  font-size:13px;
  font-weight:700;
  color:#fff;
}
.status-active{
  background:#27AE60;
}
.status-suspended{
  background:#C0392B;
}

.buttons{
  margin-top:30px;
  display:flex;
  gap:15px;
  flex-wrap:wrap;
}

.btn{
  padding:12px 25px;
  border:none;
  border-radius:8px;
  cursor:pointer;
  font-size:15px;
  text-decoration:none;
  display:inline-flex;
  align-items:center;
  gap:8px;
  transition: background .2s;
}

.edit{
  background: var(--orange);
  color:white;
}
.edit:hover,
.edit:focus-visible{
  background: var(--orange-dark);
}

.back{
  background:#555;
  color:white;
}
.back:hover,
.back:focus-visible{
  background:#333;
}

.btn:focus-visible{
  outline:3px solid #FFD8C9;
  outline-offset:2px;
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

  .info{
    grid-template-columns:1fr;
  }
}

@media (max-width: 480px){
  .content{
    padding:18px;
  }
  .header{
    padding:0 16px;
  }
  .profile-card{
    padding:20px;
  }
  .buttons{
    flex-direction:column;
  }
  .btn{
    width:100%;
    justify-content:center;
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
    <li><a href="#" class="active" aria-current="page"><i class="fa fa-user"></i> My Profile</a></li>
    <li><a href="change_password.php"><i class="fa fa-lock"></i> Change Password</a></li>
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
      <h1>My Profile</h1>
    </div>

    <div class="user">
      <i class="fa fa-user-circle"></i>
      <span class="user-name"><?php echo htmlspecialchars($studentName); ?></span>
    </div>
  </div>

  <main class="content" id="main-content">

    <div class="profile-card">

      <div class="profile-top">
        <div class="avatar" aria-hidden="true"><?php echo htmlspecialchars(initials($studentName)); ?></div>
        <h2><?php echo htmlspecialchars($studentName); ?></h2>
        <p>TEVETA Student</p>
      </div>

      <div class="info">

        <div class="box">
          <label>Full Name</label>
          <p><?php echo htmlspecialchars($studentName); ?></p>
        </div>

        <div class="box">
          <label>Username</label>
          <p><?php echo htmlspecialchars($username); ?></p>
        </div>

        <div class="box">
          <label>College</label>
          <p><?php echo htmlspecialchars($college); ?></p>
        </div>

        <div class="box">
          <label>Course</label>
          <p><?php echo htmlspecialchars($course); ?></p>
        </div>

        <div class="box">
          <label>Level</label>
          <p><?php echo htmlspecialchars($level); ?></p>
        </div>

        <div class="box">
          <label>Account Status</label>
          <p>
            <span class="status-badge <?php echo strtolower($accountStatus) === 'active' ? 'status-active' : 'status-suspended'; ?>">
              <?php echo htmlspecialchars($accountStatus); ?>
            </span>
          </p>
        </div>

      </div>

      <div class="buttons">
        <!-- TODO (BACKEND): point this to your actual edit-profile page/handler -->
        <a href="edit_profile.php" class="btn edit">
          <i class="fa fa-pen"></i>
          Edit Profile
        </a>

        <a href="dashboard.php" class="btn back">
          <i class="fa fa-arrow-left"></i>
          Back to Dashboard
        </a>
      </div>

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
</script>

</body>
</html>