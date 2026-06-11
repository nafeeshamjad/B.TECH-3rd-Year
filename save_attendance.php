<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['student_id'])) {
        die("Student ID not received from form.");
    }

    $student_id = $_POST['student_id'];
    $status = $_POST['status'];

    $date = date("Y-m-d");
    $time = date("H:i:s");

    // Check if attendance exists today
    $check = "SELECT * FROM attendance WHERE student_id = '$student_id' AND date = '$date'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {

        // Update existing attendance
        $update = "UPDATE attendance 
                   SET status = '$status', time = '$time' 
                   WHERE student_id = '$student_id' AND date = '$date'";
        mysqli_query($conn, $update);

    } else {

        // Insert attendance
        $insert = "INSERT INTO attendance (student_id, status, date, time)
                   VALUES ('$student_id', '$status', '$date', '$time')";
        mysqli_query($conn, $insert);
    }

    header("Location: mark_attendance.php?msg=Attendance Saved");
    exit();
}
?>
