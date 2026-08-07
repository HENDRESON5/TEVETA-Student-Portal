<?php
/* ============================================================
   AUTH.PHP
   Login checking + session helpers, shared by login.php and
   every protected page in student/ and admin/.
 */

require_once __DIR__ . '/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Attempt to log a user in with username + password.
 * On success: sets session variables and returns true.
 * On failure: returns a string with the reason (shown to the user).
 */
function attempt_login($conn, $username, $password) {
    $username = trim($username);

    if ($username === '' || $password === '') {
        return "Please enter both username and password.";
    }

    $stmt = mysqli_prepare($conn, "SELECT id, fullname, username, password, role, status FROM users WHERE username = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$user) {
        // Deliberately vague - don't reveal whether the username exists.
        return "Incorrect username or password.";
    }

    if ($user['status'] === 'Inactive') {
        return "This account has been deactivated. Please contact an administrator.";
    }

    if (!password_verify($password, $user['password'])) {
        return "Incorrect username or password.";
    }

    // Success - store the essentials in the session.
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['role']     = $user['role']; // "Admin" or "Student"

    // If this user is a student, also grab their student_id
    // (different from users.id) since result/results pages key off it.
    if ($user['role'] === 'Student') {
        $stmt2 = mysqli_prepare($conn, "SELECT student_id FROM students WHERE user_id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt2, "i", $user['id']);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);
        $studentRow = mysqli_fetch_assoc($result2);
        mysqli_stmt_close($stmt2);

        if ($studentRow) {
            $_SESSION['student_id'] = $studentRow['student_id'];
        }
    }

    return true;
}

/**
 * Call this at the top of any page that requires a logged-in user
 * (both student and admin pages). Redirects to login if not logged in.
 *
 * $loginPath: relative path back to login.php from wherever this is called.
 *             e.g. student/dashboard.php and admin/dashboard.php are both
 *             one folder deep, so both use the default '../login.php'.
 */
function require_login($loginPath = '../login.php') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . $loginPath);
        exit;
    }
}

/**
 * Call this after require_login() on pages that should only be
 * accessible to one role (e.g. admin-only pages, student-only pages).
 */
function require_role($role, $redirectPath = '../login.php') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: " . $redirectPath);
        exit;
    }
}

/**
 * Destroys the session completely. Call this from logout.php.
 */
function do_logout() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];
    session_destroy();
}
