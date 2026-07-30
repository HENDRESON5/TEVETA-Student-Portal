<?php
session_start();
include("../config/database.php");

// Check if student is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$student = $_SESSION['student'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Dashboard | TEVETA Student Portal</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f5f5;
    font-family:Arial, Helvetica, sans-serif;
}

.navbar{
    background:#ff6600;
}

.navbar-brand{
    color:white !important;
    font-weight:bold;
}

.card{
    border:none;
    border-radius:15px;
    transition:.3s;
}

.card:hover{
    transform:translateY(-5px);
    box-shadow:0 8px 20px rgba(0,0,0,.2);
}

.btn-orange{
    background:#ff6600;
    color:white;
}

.btn-orange:hover{
    background:#e65c00;
    color:white;
}

.welcome{
    background:white;
    border-left:6px solid #ff6600;
    border-radius:10px;
}

footer{
    background:#ff6600;
    color:white;
    padding:15px;
    text-align:center;
    margin-top:60px;
}

</style>

</head>

<body>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg">

<div class="container">

<a class="navbar-brand" href="#">
TEVETA STUDENT PORTAL
</a>

</div>

</nav>

<div class="container mt-5">

<div class="welcome p-4 mb-4">

<h3>Welcome,
<?php echo htmlspecialchars($student); ?>
</h3>

<p>
You have successfully logged into your student dashboard.
Use the buttons below to manage your account.
</p>

</div>

<div class="row">

<div class="col-md-4 mb-4">

<div class="card p-4 text-center">

<h4>📄 Results</h4>

<p>
View your examination results.
</p>

<a href="results.php" class="btn btn-orange">
View Results
</a>

</div>

</div>

<div class="col-md-4 mb-4">

<div class="card p-4 text-center">

<h4>👤 Profile</h4>

<p>
View and edit your profile.
</p>

<a href="profile.php" class="btn btn-orange">
My Profile
</a>

</div>

</div>

<div class="col-md-4 mb-4">

<div class="card p-4 text-center">

<h4>🚪 Logout</h4>

<p>
Sign out from the portal safely.
</p>

<a href="../logout.php" class="btn btn-danger">
Logout
</a>

</div>

</div>

</div>

</div>

<footer>

© 2026 TEVETA Student Portal | Developed by <b>Promise Henderson Mw</b> & <b>Lonjezo Makhaula</b>

</footer>

</body>
</html>