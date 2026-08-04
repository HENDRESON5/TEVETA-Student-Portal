<?php
// Enable mysqli to throw exceptions on errors instead of failing silently.
// This MUST be set before mysqli_connect() for try/catch blocks
// elsewhere (like register.php) to actually catch database failures.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost";
$user = "root";
$password = "";
$database = "teveta_student_portal";

try {
    $conn = mysqli_connect($host, $user, $password, $database);
} catch (mysqli_sql_exception $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>