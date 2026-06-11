<?php
// Database connection for attendance_system

$host = "localhost";
$user = "root";          // default XAMPP MySQL user
$pass = "";              // default XAMPP MySQL password is empty
$dbname = "attendance_system";  // your database name

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
