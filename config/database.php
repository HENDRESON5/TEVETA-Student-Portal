<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "teveta_student_portal";

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn){
    die("Database connection failed: " . mysqli_connect_error());
}
?>