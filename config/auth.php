<?php
session_start();
require_once 'db.php';

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Please enter your username and password.";
        header("Location: login.php");
        exit();
    }

    // Get student by username
    $stmt = $conn->prepare("SELECT id, full_name, username, password FROM students WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $student = $result->fetch_assoc();

        // If passwords are hashed
        if (password_verify($password, $student['password'])) {

            $_SESSION['student'] = [
                "id" => $student['id'],
                "name" => $student['full_name'],
                "username" => $student['username']
            ];

            header("Location: dashboard.php");
            exit();

        } else {
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: login.php");
            exit();
        }

    } else {
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: login.php");
        exit();
    }
}
?>