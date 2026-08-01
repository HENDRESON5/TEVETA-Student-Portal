<?php
session_start();

// Temporary student name
$studentName = "Lonjezo Makhaula";

/* ============================================================
    (BACKEND):
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>View Results — TEVETA Student Portal</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>

:root{
  --orange: #F15A22;
  --orange-dark: #d84d17;
  --bg: #f4f6f9;
  --text-muted: #777;
  --sidebar-width: 250px;

  --pass: #2E86DE;
  --credit: #E67E22;
  --distinction: #27AE60;
  --fail: #C0392B;
  --pending: #95A5A6;
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

.legend{
  display:flex;
  flex-wrap:wrap;
  gap:14px;
  margin-bottom:25px;
}

.legend-item{
  display:flex;
  align-items:center;
  gap:8px;
  font-size:13px;
  color:#555;
}

.legend-dot{
  width:12px;
  height:12px;
  border-radius:50%;
  display:inline-block;
}

.paper-section{
  background:#fff;
  padding:25px;
  border-radius:10px;
  box-shadow:0 5px 15px rgba(0,0,0,.05);
  margin-bottom:25px;
  overflow-x:auto;
}

.paper-section h2{
  color: var(--orange);
  margin-bottom:18px;
  font-size:19px;
  display:flex;
  align-items:center;
  gap:10px;
}

.paper-section h2 i{
  font-size:18px;
}

table{
  width:100%;
  border-collapse:collapse;
  min-width:560px;
}

th, td{
  padding:14px 15px;
  text-align:left;
  border-bottom:1px solid #eee;
  font-size:14px;
}

th{
  background: var(--orange);
  color:#fff;
  font-weight:600;
}

tbody tr:hover td{
  background:#fafafa;
}

.badge{
  display:inline-block;
  padding:5px 12px;
  border-radius:20px;
  font-size:12.5px;
  font-weight:700;
  color:#fff;
  white-space:nowrap;
}

.badge-pass        { background: var(--pass); }
.badge-credit      { background: var(--credit); }
.badge-distinction { background: var(--distinction); }
.badge-fail        { background: var(--fail); }
.badge-pending     { background: var(--pending); }

.comment-cell{
  color:#555;
  max-width:260px;
}
.comment-cell.empty{
  color: var(--text-muted);
  font-style:italic;
}

.empty-state{
  text-align:center;
  color: var(--text-muted);
  padding:20px 0 !important;
  font-style:italic;
}

footer{
  margin-top:10px;
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
}

@media (max-width: 480px){
  .content{
    padding:18px;
  }
  .header{
    padding:0 16px;
  }
  .paper-section{
    padding:18px;
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
    <li><a href="#" class="active" aria-current="page"><i class="fa fa-chart-bar"></i> View Results</a></li>
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
      <h1>My Examination Results</h1>
    </div>

    <div class="user">
      <i class="fa fa-user-circle"></i>
      <span class="user-name"><?php echo htmlspecialchars($studentName); ?></span>
    </div>
  </div>

  <main class="content" id="main-content">

    <div class="legend" aria-hidden="true">
      <span class="legend-item"><span class="legend-dot" style="background:var(--distinction)"></span> Distinction</span>
      <span class="legend-item"><span class="legend-dot" style="background:var(--credit)"></span> Credit</span>
      <span class="legend-item"><span class="legend-dot" style="background:var(--pass)"></span> Pass</span>
      <span class="legend-item"><span class="legend-dot" style="background:var(--fail)"></span> Fail</span>
    </div>

    <!-- PRACTICAL PAPER -->
    <div class="paper-section">
      <h2><i class="fa fa-screwdriver-wrench"></i> Practical Paper</h2>
      <table>
        <thead>
          <tr>
            <th>Subject</th>
            <th>Score</th>
            <th>Classification</th>
            <th>Administration Comment</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($resultsData["practical"])): ?>
            <tr><td colspan="4" class="empty-state">No practical paper results published yet.</td></tr>
          <?php else: ?>
            <?php foreach ($resultsData["practical"] as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars($row["subject"]); ?></td>
                <td><?php echo $row["score"] !== null ? htmlspecialchars($row["score"]) : "—"; ?></td>
                <td><span class="badge <?php echo classBadge($row["classification"]); ?>"><?php echo htmlspecialchars($row["classification"]); ?></span></td>
                <td class="comment-cell<?php echo empty($row["comment"]) ? " empty" : ""; ?>">
                  <?php echo $row["comment"] !== "" ? htmlspecialchars($row["comment"]) : "No comment from administration."; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- OCCUPATIONAL PAPER -->
    <div class="paper-section">
      <h2><i class="fa fa-briefcase"></i> Occupational Paper</h2>
      <table>
        <thead>
          <tr>
            <th>Subject</th>
            <th>Score</th>
            <th>Classification</th>
            <th>Administration Comment</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($resultsData["occupational"])): ?>
            <tr><td colspan="4" class="empty-state">No occupational paper results published yet.</td></tr>
          <?php else: ?>
            <?php foreach ($resultsData["occupational"] as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars($row["subject"]); ?></td>
                <td><?php echo $row["score"] !== null ? htmlspecialchars($row["score"]) : "—"; ?></td>
                <td><span class="badge <?php echo classBadge($row["classification"]); ?>"><?php echo htmlspecialchars($row["classification"]); ?></span></td>
                <td class="comment-cell<?php echo empty($row["comment"]) ? " empty" : ""; ?>">
                  <?php echo $row["comment"] !== "" ? htmlspecialchars($row["comment"]) : "No comment from administration."; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- FUNDAMENTAL PAPER -->
    <div class="paper-section">
      <h2><i class="fa fa-book"></i> Fundamental Paper</h2>
      <table>
        <thead>
          <tr>
            <th>Subject</th>
            <th>Score</th>
            <th>Classification</th>
            <th>Administration Comment</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($resultsData["fundamental"])): ?>
            <tr><td colspan="4" class="empty-state">No fundamental paper results published yet.</td></tr>
          <?php else: ?>
            <?php foreach ($resultsData["fundamental"] as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars($row["subject"]); ?></td>
                <td><?php echo $row["score"] !== null ? htmlspecialchars($row["score"]) : "—"; ?></td>
                <td><span class="badge <?php echo classBadge($row["classification"]); ?>"><?php echo htmlspecialchars($row["classification"]); ?></span></td>
                <td class="comment-cell<?php echo empty($row["comment"]) ? " empty" : ""; ?>">
                  <?php echo $row["comment"] !== "" ? htmlspecialchars($row["comment"]) : "No comment from administration."; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- TODO (BACKEND): consider adding an "Overall Status" summary card here
         (e.g. total credits earned, overall pass/fail, download-certificate
         button once all three paper types are graded and passed). -->

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