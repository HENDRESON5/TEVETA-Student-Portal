<?php
session_start();

// Temporary student name
$studentName = "LORGODAN MAKHAULA";
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Request Results — TEVETA Student Portal</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>

:root{
  --orange: #F15A22;
  --orange-dark: #d84d17;
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
  box-shadow:0 2px 8px rgba(0,0,0,.05);
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

.card{
  background:#fff;
  padding:30px;
  border-radius:10px;
  box-shadow:0 5px 15px rgba(0,0,0,.05);
}

.card h2{
  color: var(--orange);
  margin-bottom:20px;
  font-size:20px;
}

.form-grid{
  display:grid;
  grid-template-columns:repeat(2, 1fr);
  gap:18px;
}

.form-group{
  margin-bottom:0;
}

label{
  display:block;
  margin-bottom:8px;
  font-weight:600;
  font-size:14px;
  color:#333;
}

select{
  width:100%;
  padding:12px;
  border:1px solid #ccc;
  border-radius:8px;
  font-size:15px;
  background:#fff;
  transition: border-color .2s, box-shadow .2s;
}

select:focus{
  outline:none;
  border-color: var(--orange);
  box-shadow: 0 0 0 3px rgba(241,90,34,0.15);
}

button[type="submit"]{
  margin-top:24px;
  padding:14px;
  width:100%;
  border:none;
  background: var(--orange);
  color:#fff;
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

.info{
  margin-top:30px;
  background:#fff8f4;
  border-left:5px solid var(--orange);
  padding:20px;
  border-radius:8px;
}

.info h3{
  color: var(--orange);
  margin-bottom:10px;
  font-size:16px;
}

.info ul{
  padding-left:20px;
  color:#555;
  line-height:1.7;
}

footer{
  margin-top:30px;
  text-align:center;
  color: var(--text-muted);
  font-size:13px;
  line-height:1.6;
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

  .form-grid{
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
  .card{
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
    <li><a href="#" class="active" aria-current="page"><i class="fa fa-file-alt"></i> Request Results</a></li>
    <li><a href="results.php"><i class="fa fa-list"></i> View Results</a></li>
    <li><a href="profile.php"><i class="fa fa-user"></i> My Profile</a></li>
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
      <h1>Request Examination Results</h1>
    </div>

    <div class="user">
      <i class="fa fa-user-circle"></i>
      <span class="user-name"><?php echo htmlspecialchars($studentName); ?></span>
    </div>
  </div>

  <main class="content" id="main-content">

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

    <div class="info">
      <h3>Important Information</h3>
      <ul>
        <li>You can only request one examination results at a time.</li>
        <li>Your request will be reviewed by the administrator.</li>
        <li>You will be notified once your results are available.</li>
        <li>Ensure the selected college, course and level are correct.</li>
      </ul>
    </div>

    <footer>
      &copy; 2026 TEVETA Student Portal<br>
    
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