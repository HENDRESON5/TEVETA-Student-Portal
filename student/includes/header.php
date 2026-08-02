<?php
/* ============================================================
   SHARED STUDENT HEADER INCLUDE
   Every student page includes this after setting:
     $pageTitle   - shown in <title> and the topbar h1
     $activeMenu  - one of: dashboard | request | myrequests | results | profile | password
     $studentName - the logged-in student's name (from session/DB)

   Example (top of any student page):
     $pageTitle  = "My Profile";
     $activeMenu = "profile";
     require 'includes/header.php';
   ============================================================ */

if (!isset($pageTitle))   $pageTitle = "Student Portal";
if (!isset($activeMenu))  $activeMenu = "";
if (!isset($studentName)) $studentName = "Student";

function navClass($menuKey, $activeMenu) {
  return $menuKey === $activeMenu ? "active" : "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo htmlspecialchars($pageTitle); ?> — TEVETA Student Portal</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/student.css">

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
    <li><a href="dashboard.php" class="<?php echo navClass('dashboard', $activeMenu); ?>" <?php echo $activeMenu === 'dashboard' ? 'aria-current="page"' : ''; ?>><i class="fa fa-home"></i> Dashboard</a></li>
    <li><a href="request_results.php" class="<?php echo navClass('request', $activeMenu); ?>" <?php echo $activeMenu === 'request' ? 'aria-current="page"' : ''; ?>><i class="fa fa-file-alt"></i> Request Results</a></li>
   
    <li><a href="view_results.php" class="<?php echo navClass('results', $activeMenu); ?>" <?php echo $activeMenu === 'results' ? 'aria-current="page"' : ''; ?>><i class="fa fa-chart-bar"></i> View Results</a></li>
    <li><a href="profile.php" class="<?php echo navClass('profile', $activeMenu); ?>" <?php echo $activeMenu === 'profile' ? 'aria-current="page"' : ''; ?>><i class="fa fa-user"></i> My Profile</a></li>
    <li><a href="change_password.php" class="<?php echo navClass('password', $activeMenu); ?>" <?php echo $activeMenu === 'password' ? 'aria-current="page"' : ''; ?>><i class="fa fa-lock"></i> Change Password</a></li>
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
      <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
    </div>

    <div class="user">
      <i class="fa fa-user-circle"></i>
      <span class="user-name"><?php echo htmlspecialchars($studentName); ?></span>
    </div>
  </div>

  <main class="content" id="main-content">
