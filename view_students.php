<?php
session_start();
require 'config.php';

// Only admin can view this page
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all students
$query = "SELECT id, name, email FROM users WHERE role = 'student'";
$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html>
<head>
    <title>View All Students</title>
    <style>
    /* ===== Global ===== */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: "Poppins", Arial, sans-serif;
        min-height: 100vh;
        background: radial-gradient(circle at top left, #c9ddff, #edf2ff 45%, #dde7ff);
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 40px 16px 32px;
    }

    /* ===== Heading ===== */
    h2 {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        letter-spacing: 0.02em;
        text-align: center;
        margin-bottom: 18px;
    }

    /* ===== Table ===== */
    table {
        width: 95%;
        max-width: 1000px;
        margin-top: 10px;
        background: #ffffff;
        border-collapse: collapse;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
    }

    thead tr {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }

    th, td {
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
    }

    th {
        color: #ffffff;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    tbody tr:nth-child(even) {
        background: #f9fafb;
    }

    tbody tr:hover {
        background: #eff6ff;
        transition: background 0.2s ease, transform 0.1s ease;
        transform: translateY(-1px);
    }

    td:first-child,
    th:first-child {
        text-align: center;
        width: 80px;
    }

    /* ===== Back Button ===== */
    .back-btn {
        display: block;
        margin-top: 26px;
        padding: 12px 24px;
        border-radius: 999px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        letter-spacing: 0.01em;
        text-align: center;
        box-shadow: 0 8px 18px rgba(37, 99, 235, 0.35);
        transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease;
    }

    .back-btn:hover {
        filter: brightness(1.05);
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.45);
    }

    /* ===== Responsive ===== */
    @media (max-width: 640px) {
        h2 {
            font-size: 22px;
        }

        table {
            font-size: 13px;
        }

        th, td {
            padding: 10px 8px;
        }
    }
</style>

</head>
<body>

<h2 style="text-align:center; margin-top:30px;">All Registered Students</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Student Name</th>
        <th>Email</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['id']; ?></td>
        <td><?= $row['name']; ?></td>
        <td><?= $row['email']; ?></td>
    </tr>
    <?php } ?>

</table>

<a class="back-btn" href="admin_dashboard.php">Back</a>

</body>
</html>
