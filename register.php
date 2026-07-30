<?php
session_start();

$message = "";

if(isset($_POST['register'])){

    $fullname = $_POST['fullname'];
    $college = $_POST['college'];
    $course = $_POST['course'];
    $level = $_POST['level'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if($password != $confirm){
        $message = "Passwords do not match!";
    }else{
        // Database code will be added later
        $message = "Registration successful!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register | Student Portal</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:linear-gradient(135deg,#F15A22,#ffffff);
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    padding:30px;
}

.register-box{
    width:450px;
    background:#fff;
    padding:35px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
}

.logo{
    display:block;
    margin:auto;
    width:90px;
}

h2{
    text-align:center;
    color:#F15A22;
    margin:10px 0;
}

.subtitle{
    text-align:center;
    color:#777;
    margin-bottom:20px;
}

.message{
    color:green;
    text-align:center;
    margin-bottom:15px;
}

.error{
    color:red;
    text-align:center;
    margin-bottom:15px;
}

input,select{
    width:100%;
    padding:12px;
    margin:8px 0;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:15px;
}

input:focus,
select:focus{
    border-color:#F15A22;
    outline:none;
}

button{
    width:100%;
    padding:13px;
    background:#F15A22;
    color:#fff;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
    margin-top:10px;
}

button:hover{
    background:#d94d18;
}

.bottom{
    text-align:center;
    margin-top:20px;
}

.bottom a{
    color:#F15A22;
    text-decoration:none;
    font-weight:bold;
}

.bottom a:hover{
    text-decoration:underline;
}

</style>

</head>
<body>

<div class="register-box">

<img src="images/teveta-logo.png" class="logo">

<h2>Create Account</h2>
<p class="subtitle">TEVETA Student Portal</p>

<?php
if($message!=""){
    if($message=="Registration successful!"){
        echo "<p class='message'>$message</p>";
    }else{
        echo "<p class='error'>$message</p>";
    }
}
?>

<form method="POST">

<input
type="text"
name="fullname"
placeholder="Full Name"
required>

<!-- COLLEGES -->

<select name="college" required>
<option value="">Select College</option>
<option>Salima Technical College</option>
<option>Lilongwe Technical College</option>
<option>Mzuzu Technical College</option>
<option>Nasawa Technical College</option>
<option>Namitete Technical College</option>
<option>Namitembo Technical College</option>
<option>Phwezi Technical College</option>
<option>Sochi Technical College</option>
<option>TEEM Technical College</option>
<option>Zomba Technical College</option>
<option>Zomba Technical College</option>
<option>Zomba Technical College</option>
<option>Zomba Technical College</option>
<option>Zomba Technical College</option>
<option>Other</option>
</select>

<!-- STUDENT COURSE -->
<select name="course" required>
<option value="">Select Course</option>
<option>Information communication technology </option>
<option>Plumbing</option>
</select>

<!-- STUDENTS LEVELS -->
<select name="level" required>
<option value="">Select Level</option>
<option>Level 1</option>
<option>Level 2</option>
<option>Level 3</option>
<option>Level 4</option>
</select>

<input
type="text"
name="username"
placeholder="Username"
required>

<input
type="password"
name="password"
placeholder="Password"
required>

<input
type="password"
name="confirm"
placeholder="Confirm Password"
required>

<button type="submit" name="register">
Create Account
</button>

</form>

<div class="bottom">
Already have an account?
<a href="login.php">Login</a>
</div>

</div>

</body>
</html>