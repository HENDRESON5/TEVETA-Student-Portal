<?php
session_start();

// Temporary user data (replace with database later)
$username = "LORGODAN MAKHAULA";
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student Dashboard — TEVETA Student Portal</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

:root{
  --orange: #F15A22;
  --orange-dark: #d94d18;
  --bg: #f4f6f9;
  --text-dark: #333;
  --text-muted: #777;
  --sidebar-width: 260px;
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

/* SKIP LINK - keyboard users can jump straight to content */
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

/* SIDEBAR */

.sidebar{
  width: var(--sidebar-width);
  height:100vh;
  background:#ffffff;
  box-shadow:2px 0 10px rgba(0,0,0,.08);
  position:fixed;
  left:0;
  top:0;
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
  color:#444;
  text-decoration:none;
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

/* MAIN */

.main{
  margin-left: var(--sidebar-width);
  width: calc(100% - var(--sidebar-width));
  transition: margin-left .3s ease;
}

/* TOPBAR */

.topbar{
  height:70px;
  background:white;
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:0 30px;
  box-shadow:0 2px 10px rgba(0,0,0,.05);
  position:sticky;
  top:0;
  z-index:50;
}

.topbar-left{
  display:flex;
  align-items:center;
  gap:16px;
}

.menu-toggle{
  display:none;
  background:none;
  border:none;
  font-size:22px;
  color: var(--orange);
  cursor:pointer;
  padding:6px;
}

.portal-title{
  font-size:24px;
  font-weight:bold;
  color: var(--orange);
}

.user{
  font-weight:600;
  color:#555;
  display:flex;
  align-items:center;
  gap:8px;
  white-space:nowrap;
}

.content{
  padding:30px;
}

/* WELCOME */

.welcome{
  background:white;
  border-left:6px solid var(--orange);
  padding:25px;
  border-radius:10px;
  margin-bottom:25px;
  box-shadow:0 5px 15px rgba(0,0,0,.05);
}

.welcome h2{
  color: var(--orange);
  margin-bottom:10px;
}

.welcome p{
  color:#555;
  line-height:1.5;
}

/* CARDS */

.cards{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
  gap:20px;
  margin-bottom:30px;
}

.card{
  background:white;
  padding:25px;
  border-radius:10px;
  box-shadow:0 5px 15px rgba(0,0,0,.05);
  text-align:center;
}

.card i{
  font-size:36px;
  color: var(--orange);
  margin-bottom:10px;
}

.card h3{
  font-size:32px;
  color: var(--text-dark);
}

.card p{
  color: var(--text-muted);
  font-size:14px;
  margin-top:4px;
}

/* QUICK ACTIONS */

.actions{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
  gap:20px;
  margin-bottom:30px;
}

.action{
  background:white;
  padding:30px;
  text-align:center;
  border-radius:10px;
  text-decoration:none;
  color: var(--text-dark);
  box-shadow:0 5px 15px rgba(0,0,0,.05);
  transition: background .2s, color .2s, transform .2s;
}

.action:hover,
.action:focus-visible{
  background: var(--orange);
  color:white;
  transform:translateY(-5px);
  outline:none;
}

.action i{
  font-size:40px;
  margin-bottom:15px;
  display:block;
}

.action h3{
  font-size:16px;
  font-weight:600;
}

/* RECENT ACTIVITY */

.activity{
  background:white;
  padding:25px;
  border-radius:10px;
  box-shadow:0 5px 15px rgba(0,0,0,.05);
  overflow-x:auto;
}

.activity h2{
  color: var(--orange);
  margin-bottom:20px;
  font-size:20px;
}

.activity table{
  width:100%;
  border-collapse:collapse;
  min-width:420px;
}

.activity th,
.activity td{
  padding:15px;
  border-bottom:1px solid #eee;
  text-align:left;
  font-size:14px;
}

.activity th{
  background: var(--orange);
  color:white;
}

.activity tbody tr:hover td{
  background:#fafafa;
}

.empty-state{
  text-align:center;
  color: var(--text-muted);
  padding:20px 0 !important;
}

footer{
  text-align:center;
  padding:20px;
  color: var(--text-muted);
  margin-top:10px;
  font-size:13px;
}

/* OVERLAY for mobile sidebar */
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

  .portal-title{
    font-size:19px;
  }

  .user span.user-name{
    display:none; /* keep icon only on very small screens */
  }
}

@media (max-width: 480px){
  .content{
    padding:18px;
  }
  .topbar{
    padding:0 16px;
  }
  .user span.user-name{
    display:inline;
    font-size:13px;
  }
}

@media (prefers-reduced-motion: reduce){
  *{ transition:none !important; }
}

</style>

</head>

<body>

<a href="#main-content" class="skip-link">Skip to content</a>

<!-- SIDEBAR -->
<nav class="sidebar" id="sidebar" aria-label="Main navigation">

  <div class="logo">
    <img src="../images/teveta-logo.png" alt="TEVETA logo">
    <h2>Student Portal</h2>
  </div>

  <ul class="menu">
    <li><a href="#" class="active" aria-current="page"><i class="fa fa-home"></i> Dashboard</a></li>
    <li><a href="request_results.php"><i class="fa fa-file"></i> Request Results</a></li>
    <li><a href="results.php"><i class="fa fa-list"></i> View Results</a></li>
    <li><a href="profile.php"><i class="fa fa-user"></i> My Profile</a></li>
    <li><a href="change_password.php"><i class="fa fa-lock"></i> Change Password</a></li>
    <li><a href="../logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
  </ul>

</nav>

<div class="overlay" id="overlay"></div>

<!-- MAIN -->
<div class="main">

  <div class="topbar">

    <div class="topbar-left">
      <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="sidebar">
        <i class="fa fa-bars"></i>
      </button>
      <div class="portal-title">TEVETA Student Portal</div>
    </div>

    <div class="user">
      <i class="fa fa-user-circle"></i>
      <span class="user-name"><?php echo htmlspecialchars($username); ?></span>
    </div>

  </div>

  <main class="content" id="main-content">

    <div class="welcome">
      <h2>Welcome back!</h2>
      <p>
        Hello <strong><?php echo htmlspecialchars($username); ?></strong>,
        welcome to the Student Portal.
        Use the menu to request examination results,
        track your requests and manage your profile.
      </p>
    </div>

    <!-- STATISTICS -->
    <div class="cards">
      <div class="card">
        <i class="fa fa-file-alt"></i>
        <h3>0</h3>
        <p>Result Requests</p>
      </div>
      <div class="card">
        <i class="fa fa-check-circle"></i>
        <h3>0</h3>
        <p>Approved</p>
      </div>
      <div class="card">
        <i class="fa fa-clock"></i>
        <h3>0</h3>
        <p>Pending</p>
      </div>
      <div class="card">
        <i class="fa fa-bell"></i>
        <h3>0</h3>
        <p>Notifications</p>
      </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="actions">
      <a href="request_results.php" class="action">
        <i class="fa fa-file-signature"></i>
        <h3>Request Results</h3>
      </a>
      <a href="my_requests.php" class="action">
        <i class="fa fa-list-check"></i>
        <h3>View Results</h3>
      </a>
      <a href="profile.php" class="action">
        <i class="fa fa-user-edit"></i>
        <h3>My Profile</h3>
      </a>
    </div>

    <!-- RECENT ACTIVITY -->
    <div class="activity">
      <h2>Recent Activity</h2>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Activity</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="3" class="empty-state">No activity yet — your requests will show up here.</td>
          </tr>
        </tbody>
      </table>
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

  // Close sidebar automatically when a nav link is tapped on mobile
  document.querySelectorAll('.menu a').forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 900) closeSidebar();
    });
  });
</script>

</body>
</html>