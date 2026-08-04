<?php
$message = "";

if(isset($_POST['submit'])){
    $email = trim($_POST['email']);

    if(empty($email)){
        $message = "<p style='color:red;'>Please enter your registered email address.</p>";
    }else{
        // TODO:
        // Check if email exists in database
        // Generate reset token or OTP
        // Send password reset email

        $message = "<p style='color:green;'>If your email exists in our system, a password reset link will be sent to you.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password | TEVETA Student Portal</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Segoe UI, Arial, sans-serif;
}

body{
    background:linear-gradient(135deg,#F15A22,#ffffff);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.forgot-box{
    width:380px;
    background:#fff;
    border-radius:15px;
    padding:35px;
    box-shadow:0 15px 35px rgba(0,0,0,.15);
    text-align:center;
}

.logo{
    width:120px;
    margin-bottom:10px;
}

h2{
    color:#F15A22;
    margin-bottom:5px;
}

p{
    color:#6F7378;
    margin-bottom:20px;
    font-size:14px;
}

.input-box{
    width:100%;
    padding:12px;
    margin:10px 0;
    border:1px solid #BFC3C7;
    border-radius:8px;
    outline:none;
    transition:.3s;
}

.input-box:focus{
    border-color:#F15A22;
}

button{
    width:100%;
    padding:12px;
    margin-top:15px;
    background:#F15A22;
    color:#fff;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#d94d18;
}

.links{
    margin-top:20px;
    font-size:14px;
}

.links a{
    color:#F15A22;
    text-decoration:none;
    font-weight:600;
}

.links a:hover{
    text-decoration:underline;
}

.message{
    margin-bottom:15px;
}
</style>

</head>
<body>

<div class="forgot-box">

    <img src="images/teveta-logo.png" class="logo" alt="TEVETA Logo">

    <h2>Forgot Password</h2>
    <p>Enter your registered email address to reset your password.</p>

    <div class="message">
        <?php echo $message; ?>
    </div>

    <form method="POST">

        <input
            type="email"
            name="email"
            class="input-box"
            placeholder="Email Address"
            required
        >

        <button type="submit" name="submit">
            Send Reset Link
        </button>

    </form>

    <div class="links">
        <a href="login.php">← Back to Login</a>
    </div>

</div>

</body>
</html>