<?php
/* ============================================================
   SHARED ADMIN HEADER INCLUDE
   Every admin page includes this after setting:
     $pageTitle   - shown in <title> and the topbar h1
     $activeMenu  - one of: dashboard | students | requests | account
     $adminName   - the logged-in admin's name (from session/DB)

   Example (top of any admin page):
     $pageTitle  = "Registered Students";
     $activeMenu = "students";
     require 'includes/header.php';
   ============================================================ */

if (!isset($pageTitle))  $pageTitle = "Admin Panel";
if (!isset($activeMenu)) $activeMenu = "";
if (!isset($adminName))  $adminName = "Administrator";

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
<link rel="stylesheet" href="assets/css/admin.css">

</head>
<body>

<a href="#main-content" class="skip-link">Skip to content</a>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar" aria-label="Admin navigation">

  <div class="logo">
    <img src="../images/teveta-logo.png" alt="TEVETA logo">
    <h2>Admin Panel</h2>
    <p>TEVETA Student Portal</p>
  </div>

  <ul class="menu">
    <li><a href="dashboard.php" class="<?php echo navClass('dashboard', $activeMenu); ?>" <?php echo $activeMenu === 'dashboard' ? 'aria-current="page"' : ''; ?>><i class="fa fa-home"></i> Dashboard</a></li>
    <li><a href="students.php" class="<?php echo navClass('students', $activeMenu); ?>" <?php echo $activeMenu === 'students' ? 'aria-current="page"' : ''; ?>><i class="fa fa-users"></i> Students</a></li>
    <li><a href="result_requests.php" class="<?php echo navClass('requests', $activeMenu); ?>" <?php echo $activeMenu === 'requests' ? 'aria-current="page"' : ''; ?>><i class="fa fa-file-alt"></i> Result Requests</a></li>
    <li><a href="password_reset_requests.php" class="<?php echo navClass('resets', $activeMenu); ?>" <?php echo $activeMenu === 'resets' ? 'aria-current="page"' : ''; ?>><i class="fa fa-unlock-keyhole"></i> Password Resets</a></li>
    <li><a href="account_management.php" class="<?php echo navClass('account', $activeMenu); ?>" <?php echo $activeMenu === 'account' ? 'aria-current="page"' : ''; ?>><i class="fa fa-user-cog"></i> Account Management</a></li>
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

    <div class="admin">
      <i class="fa fa-user-circle"></i>
      <span class="admin-name"><?php echo htmlspecialchars($adminName); ?></span>
    </div>
  </div>

  <main class="content" id="main-content">
