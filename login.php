<?php
require_once 'config/auth.php';

// Already logged in? Skip straight to the right dashboard.
if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] === 'Admin' ? 'admin/dashboard.php' : 'student/dashboard.php'));
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = attempt_login($conn, $_POST['username'] ?? '', $_POST['password'] ?? '');

    if ($result === true) {
        header("Location: " . ($_SESSION['role'] === 'Admin' ? 'admin/dashboard.php' : 'student/dashboard.php'));
        exit;
    } else {
        $error = $result; // string reason returned by attempt_login()
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TEVETA Student Portal — Login</title>

<style>
  :root{
    --orange: #F15A22;
    --orange-dark: #d94d18;
    --gray-text: #6F7378;
    --border: #BFC3C7;
    --error: #C0392B;
  }

  *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: "Segoe UI", Arial, sans-serif;
  }

  body{
    background: linear-gradient(135deg, var(--orange), #ffffff);
    min-height: 100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding: 20px;
  }

  .login-box{
    width: 380px;
    max-width: 100%;
    background:#fff;
    border-radius:15px;
    padding: 35px;
    box-shadow: 0 15px 35px rgba(0,0,0,.15);
    text-align:center;
  }

  .logo{
    width:120px;
    margin-bottom:10px;
  }

  h1{
    color: var(--orange);
    font-size: 1.5rem;
    margin-bottom:5px;
  }

  p.subtitle{
    color: var(--gray-text);
    margin-bottom:25px;
    font-size:14px;
  }

  .error-banner{
    background:#FDEDEB;
    color: var(--error);
    border: 1px solid #F5C6C0;
    border-radius:8px;
    padding:10px 12px;
    font-size:13px;
    margin-bottom:16px;
    text-align:left;
  }

  form{
    text-align:left;
  }

  label{
    display:block;
    font-size:13px;
    font-weight:600;
    color:#3B3E42;
    margin-bottom:6px;
    margin-top:14px;
  }
  label:first-of-type{
    margin-top:0;
  }

  .input-box{
    width:100%;
    padding:12px;
    border:1px solid var(--border);
    border-radius:8px;
    outline:none;
    font-size:15px;
    transition: border-color .2s, box-shadow .2s;
  }

  .input-box:focus{
    border-color: var(--orange);
    box-shadow: 0 0 0 3px rgba(241,90,34,0.15);
  }

  .password-wrap{
    position:relative;
  }

  .password-wrap .input-box{
    padding-right: 44px;
  }

  .toggle-password{
    position:absolute;
    right:10px;
    top:50%;
    transform: translateY(-50%);
    background:none;
    border:none;
    cursor:pointer;
    font-size:13px;
    color: var(--gray-text);
    font-weight:600;
    padding:4px 6px;
  }
  .toggle-password:hover{
    color: var(--orange);
  }

  button[type="submit"]{
    width:100%;
    padding:12px;
    margin-top:22px;
    background: var(--orange);
    color:#fff;
    border:none;
    border-radius:8px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition: background .2s;
  }

  button[type="submit"]:hover{
    background: var(--orange-dark);
  }

  .links{
    margin-top:20px;
    font-size:14px;
    text-align:center;
    color: var(--gray-text);
  }

  .links a{
    color: var(--orange);
    text-decoration:none;
    font-weight:600;
  }
  .links a:hover{
    text-decoration:underline;
  }

  .links .divider{
    margin: 0 8px;
    color: var(--border);
  }
</style>

</head>
<body>

<div class="login-box">

  <img src="images/teveta-logo.png" class="logo" alt="TEVETA logo">

  <h1>Student Portal</h1>
  <p class="subtitle">Sign in to continue</p>

  <?php if ($error !== ""): ?>
    <div class="error-banner" role="alert"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form action="" method="POST" novalidate>

    <label for="username">Username</label>
    <input
      type="text"
      id="username"
      name="username"
      class="input-box"
      placeholder="Enter your username"
      autocomplete="username"
      required
    >

    <label for="password">Password</label>
    <div class="password-wrap">
      <input
        type="password"
        id="password"
        name="password"
        class="input-box"
        placeholder="Enter your password"
        autocomplete="current-password"
        required
      >
      <button type="button" class="toggle-password" id="togglePassword" aria-label="Show password">Show</button>
    </div>

    <button type="submit">Login</button>

  </form>

  <div class="links">
    <a href="register.php">Create Account</a>
    <span class="divider">|</span>
    <a href="forgot.php">Forgot Password?</a>
  </div>

</div>

<script>
  const toggleBtn = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');

  toggleBtn.addEventListener('click', () => {
    const isHidden = passwordInput.type === 'password';
    passwordInput.type = isHidden ? 'text' : 'password';
    toggleBtn.textContent = isHidden ? 'Hide' : 'Show';
  });
</script>

</body>
</html>