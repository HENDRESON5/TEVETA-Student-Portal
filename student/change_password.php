<?php
session_start();

// Temporary user
$studentName = "Lonjezo Makhaula";

$message = "";

if(isset($_POST['change_password'])){
    $message = "Password updated successfully! (Database connection coming later)";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Change Password</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Segoe UI',sans-serif;
}

body{
background:#f4f6f9;
display:flex;
}

/* Sidebar */

.sidebar{
width:250px;
height:100vh;
background:#fff;
position:fixed;
box-shadow:2px 0 10px rgba(0,0,0,.08);
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
color:#F15A22;
}

.menu{
margin-top:20px;
}

.menu a{
display:block;
padding:16px 25px;
text-decoration:none;
color:#444;
transition:.3s;
}

.menu a:hover,
.menu a.active{
background:#F15A22;
color:#fff;
}

.menu i{
width:25px;
}

/* Main */

.main{
margin-left:250px;
width:calc(100% - 250px);
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
}

.header h2{
color:#F15A22;
}

.user{
font-weight:600;
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
color:#F15A22;
margin-bottom:25px;
}

.form-group{
margin-bottom:20px;
}

label{
display:block;
margin-bottom:8px;
font-weight:600;
color:#444;
}

input{
width:100%;
padding:13px;
border:1px solid #ccc;
border-radius:8px;
font-size:15px;
outline:none;
}

input:focus{
border-color:#F15A22;
}

button{
width:100%;
padding:14px;
background:#F15A22;
border:none;
color:white;
font-size:16px;
border-radius:8px;
cursor:pointer;
transition:.3s;
}

button:hover{
background:#d94d18;
}

.message{
background:#d4edda;
color:#155724;
padding:15px;
border-radius:8px;
margin-bottom:20px;
}

.tips{
margin-top:30px;
background:#fff8f4;
border-left:5px solid #F15A22;
padding:20px;
border-radius:8px;
max-width:650px;
}

.tips h3{
color:#F15A22;
margin-bottom:15px;
}

.tips ul{
padding-left:20px;
color:#555;
}

footer{
text-align:center;
margin-top:30px;
color:#777;
}

</style>

</head>

<body>

<!-- Sidebar -->

<div class="sidebar">

<div class="logo">

<img src="../images/teveta-logo.png" alt="TEVETA logo">

<h2>Student Portal</h2>

</div>

<div class="menu">

<a href="dashboard.php">
<i class="fa fa-home"></i>
Dashboard
</a>

<a href="request_results.php">
<i class="fa fa-file-alt"></i>
Request Results
</a>

<a href="results.php">
<i class="fa fa-list"></i>
My Requests
</a>

<a href="profile.php">
<i class="fa fa-user"></i>
My Profile
</a>

<a href="#" class="active">
<i class="fa fa-lock"></i>
Change Password
</a>

<a href="../logout.php">
<i class="fa fa-sign-out-alt"></i>
Logout
</a>

</div>

</div>

<!-- Main -->

<div class="main">

<div class="header">

<h2>Change Password</h2>

<div class="user">

<i class="fa fa-user-circle"></i>

<?php echo $studentName; ?>

</div>

</div>

<div class="content">

<div class="card">

<h2>Update Your Password</h2>

<?php
if($message!=""){
echo "<div class='message'>$message</div>";
}
?>

<form method="POST">

<div class="form-group">

<label>Current Password</label>

<input
type="password"
name="current_password"
placeholder="Enter current password"
required>

</div>

<div class="form-group">

<label>New Password</label>

<input
type="password"
name="new_password"
placeholder="Enter new password"
required>

</div>

<div class="form-group">

<label>Confirm New Password</label>

<input
type="password"
name="confirm_password"
placeholder="Confirm new password"
required>

</div>

<button
type="submit"
name="change_password">

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

© 2026 TEVETA Student Portal

<br>



</footer>

</div>

</div>

</body>
</html>