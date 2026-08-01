<?php
session_start();

// Temporary student name
$studentName = "Lonjezo Makhaula";

/* ============================================================
<<<<<<< HEAD
   TODO (BACKEND - your friend):
   Replace $resultsData and $overall below with a real database query.

   Suggested structure once pulled from DB:

     $resultsData = [
       "practical"    => ["score" => 82, "classification" => "Distinction"],
       "occupational" => ["score" => 65, "classification" => "Pass"],
       "fundamental"  => ["score" => 70, "classification" => "Credit"],
     ];

     $overall = [
       "classification" => "Credit",     // overall result across all 3 papers
       "comment"         => "..."         // admin_comment column, may be empty
     ];

   Likely query shape (adjust to your actual schema):

     SELECT paper_type, score, classification
     FROM results
     WHERE student_id = ?;

     SELECT overall_classification, admin_comment
     FROM student_overall_result
     WHERE student_id = ?;

   If a paper type hasn't been graded yet, set its "score" to null and
   "classification" to "Pending" - the HTML below already handles that.
   ============================================================ */

$resultsData = [
  "practical"    => ["score" => 88, "classification" => "Distinction"],
  "occupational" => ["score" => 65, "classification" => "Pass"],
  "fundamental"  => ["score" => 70, "classification" => "Credit"],
];

$overall = [
  "classification" => "Credit",
  "comment"         => "Good overall performance. Improve fundamental paper score next attempt.",
];

/* TODO (BACKEND): if results aren't published yet at all, you may want
   to skip straight to an "empty state" instead of showing this table -
   e.g. check if $resultsData is null/empty before rendering. */

// Helper: maps a classification string to a CSS badge class.
// Keep this in sync with whatever classification strings the DB uses.
function classBadge($classification) {
  switch (strtolower($classification)) {
    case "distinction": return "badge-distinction";
    case "credit":       return "badge-credit";
    case "pass":         return "badge-pass";
    case "fail":         return "badge-fail";
    default:             return "badge-pending"; // e.g. "Pending"
  }
}
=======
    (BACKEND):
*/
>>>>>>> e284b9fa5d792fde6ee2dc05bfec75d30f78ccf2
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
}

table{
  width:100%;
  border-collapse:collapse;
  min-width:480px;
}

th, td{
  padding:16px 15px;
  text-align:left;
  border-bottom:1px solid #eee;
  font-size:14.5px;
}

th{
  background: var(--orange);
  color:#fff;
  font-weight:600;
}

tbody tr:hover td{
  background:#fafafa;
}

.paper-name{
  display:flex;
  align-items:center;
  gap:12px;
  font-weight:600;
  color:#333;
}

.paper-name i{
  color: var(--orange);
  font-size:18px;
  width:20px;
  text-align:center;
}

.score-value{
  font-size:17px;
  font-weight:700;
  color:#333;
}

.badge{
  display:inline-block;
  padding:6px 14px;
  border-radius:20px;
  font-size:13px;
  font-weight:700;
  color:#fff;
  white-space:nowrap;
}

.badge-pass        { background: var(--pass); }
.badge-credit      { background: var(--credit); }
.badge-distinction { background: var(--distinction); }
.badge-fail        { background: var(--fail); }
.badge-pending     { background: var(--pending); }

/* Overall summary */
.overall-card{
  background:#fff;
  border-left:6px solid var(--orange);
  padding:30px;
  border-radius:10px;
  box-shadow:0 5px 15px rgba(0,0,0,.05);
  margin-bottom:25px;
}

.overall-card h2{
  color: var(--orange);
  font-size:19px;
  margin-bottom:20px;
}

.overall-top{
  display:flex;
  align-items:center;
  justify-content:space-between;
  flex-wrap:wrap;
  gap:16px;
  margin-bottom:18px;
}

.overall-label{
  font-size:15px;
  color:#555;
  font-weight:600;
}

.overall-badge .badge{
  font-size:16px;
  padding:9px 20px;
}

.admin-comment{
  background:#fff8f4;
  border-radius:8px;
  padding:16px 18px;
}

.admin-comment h3{
  font-size:13px;
  text-transform:uppercase;
  letter-spacing:.5px;
  color: var(--orange);
  margin-bottom:8px;
}

.admin-comment p{
  color:#555;
  line-height:1.6;
  font-size:14.5px;
}

.admin-comment p.empty{
  color: var(--text-muted);
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

  .overall-top{
    flex-direction:column;
    align-items:flex-start;
  }
}

@media (max-width: 480px){
  .content{
    padding:18px;
  }
  .header{
    padding:0 16px;
  }
  .paper-section,
  .overall-card{
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
    <img src="../images/logo.png" alt="TEVETA logo">
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

    <!-- RESULTS BY PAPER -->
    <div class="paper-section">
      <h2>Results by Paper</h2>
      <table>
        <thead>
          <tr>
            <th>Paper</th>
            <th>Score</th>
            <th>Classification</th>
          </tr>
        </thead>
        <tbody>

          <tr>
            <td>
              <span class="paper-name"><i class="fa fa-screwdriver-wrench"></i> Practical Paper</span>
            </td>
            <td>
              <span class="score-value">
                <?php echo $resultsData["practical"]["score"] !== null ? htmlspecialchars($resultsData["practical"]["score"]) : "—"; ?>
              </span>
            </td>
            <td>
              <span class="badge <?php echo classBadge($resultsData["practical"]["classification"]); ?>">
                <?php echo htmlspecialchars($resultsData["practical"]["classification"]); ?>
              </span>
            </td>
          </tr>

          <tr>
            <td>
              <span class="paper-name"><i class="fa fa-briefcase"></i> Occupational Paper</span>
            </td>
            <td>
              <span class="score-value">
                <?php echo $resultsData["occupational"]["score"] !== null ? htmlspecialchars($resultsData["occupational"]["score"]) : "—"; ?>
              </span>
            </td>
            <td>
              <span class="badge <?php echo classBadge($resultsData["occupational"]["classification"]); ?>">
                <?php echo htmlspecialchars($resultsData["occupational"]["classification"]); ?>
              </span>
            </td>
          </tr>

          <tr>
            <td>
              <span class="paper-name"><i class="fa fa-book"></i> Fundamental Paper</span>
            </td>
            <td>
              <span class="score-value">
                <?php echo $resultsData["fundamental"]["score"] !== null ? htmlspecialchars($resultsData["fundamental"]["score"]) : "—"; ?>
              </span>
            </td>
            <td>
              <span class="badge <?php echo classBadge($resultsData["fundamental"]["classification"]); ?>">
                <?php echo htmlspecialchars($resultsData["fundamental"]["classification"]); ?>
              </span>
            </td>
          </tr>

        </tbody>
      </table>
    </div>

    <!-- OVERALL SUMMARY -->
    <div class="overall-card">
      <h2>Overall Result</h2>

      <div class="overall-top">
        <span class="overall-label">Final classification across all papers:</span>
        <span class="overall-badge">
          <span class="badge <?php echo classBadge($overall["classification"]); ?>">
            <?php echo htmlspecialchars($overall["classification"]); ?>
          </span>
        </span>
      </div>

      <div class="admin-comment">
        <h3>Administration Comment</h3>
        <p class="<?php echo empty($overall["comment"]) ? "empty" : ""; ?>">
          <?php echo $overall["comment"] !== "" ? htmlspecialchars($overall["comment"]) : "No comment from administration."; ?>
        </p>
      </div>
    </div>

    <!-- TODO (BACKEND): consider adding a "Download Results Slip" button
         here once the overall result is final (e.g. generates a PDF). -->

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