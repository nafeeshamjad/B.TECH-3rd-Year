<?php
include "db.php";

$query = "
SELECT attendance.*, users.name, users.roll_no 
FROM attendance
JOIN users ON attendance.user_id = users.id
ORDER BY attendance.date DESC, attendance.time DESC";

$result = mysqli_query($conn, $query);
?>

<h2>All Attendance Records</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Roll No</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
    </tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['roll_no'] ?></td>
    <td><?= $row['date'] ?></td>
    <td><?= $row['time'] ?></td>
    <td><?= $row['status'] ?></td>
</tr>
<?php } ?>
</table>
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
