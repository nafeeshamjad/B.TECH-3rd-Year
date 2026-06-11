<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

$result = mysqli_query($conn, "
    SELECT a.id, u.name, a.date, a.time, a.status
    FROM attendance a
    JOIN users u ON a.user_id = u.id
    ORDER BY a.date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance Records</title>
    <style>
        body { font-family: Arial; background: #eef2f3; }
        table {
            width: 80%; margin: 50px auto;
            border-collapse: collapse; background: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
        th, td {
            border: 1px solid #ccc; padding: 10px; text-align: center;
        }
        th { background: #007bff; color: white; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Attendance Records</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Student</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['id']; ?></td>
        <td><?= $row['name']; ?></td>
        <td><?= $row['date']; ?></td>
        <td><?= $row['time']; ?></td>
        <td><?= ucfirst($row['status']); ?></td>
    </tr>
    <?php } ?>

</table>

</body>
</html>
