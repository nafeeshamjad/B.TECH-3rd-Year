<?php
session_start();
require 'config.php';

// Only allow students to access this page
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$name = $_SESSION['user_name'];

// Fetch attendance of logged-in student
$query = "
    SELECT date, time, status 
    FROM attendance 
    WHERE user_id = '$student_id'
    ORDER BY date DESC
";

$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: Arial;
            background: #eef2f3;
        }
        .box {
            width: 500px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        a {
            display: block;
            margin: 20px auto;
            width: 150px;
            padding: 10px;
            text-align: center;
            color: white;
            background: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background: #0057c2;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Welcome, <?php echo $name; ?> 👋</h2>
    <p>Your Attendance Record:</p>
</div>

<table>
    <tr>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['date']; ?></td>
        <td><?= $row['time']; ?></td>
        <td><?= ucfirst($row['status']); ?></td>
    </tr>
    <?php } ?>
</table>

<a href="logout.php">Logout</a>

</body>
</html>
