<?php
session_start();

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// 2. Get user data from session
$userName = $_SESSION['user_name'] ?? 'User';
$userRole = $_SESSION['user_role'] ?? 'student'; // default
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Cloud Based Attendance System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
        }
        .navbar {
            background: #007bff;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            margin-left: 15px;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 20px 25px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        h2 {
            margin-top: 0;
        }
        .role-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            background: #eee;
            margin-left: 5px;
        }
        ul {
            padding-left: 18px;
        }
        .section-title {
            margin-top: 20px;
            margin-bottom: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div>
        <strong>Cloud Based Attendance System</strong>
    </div>
    <div>
        Logged in as: <?php echo htmlspecialchars($userName); ?>
        (<?php echo htmlspecialchars(ucfirst($userRole)); ?>)
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($userName); ?>!</h2>
    <p>Your role:
        <span class="role-badge">
            <?php echo htmlspecialchars(ucfirst($userRole)); ?>
        </span>
    </p>

    <?php if ($userRole === 'admin'): ?>
        <!-- ADMIN DASHBOARD -->
        <p class="section-title">Admin Options:</p>
        <ul>
            <li><a href="manage_users.php">Manage Users (Add / Edit / Delete)</a></li>
            <li><a href="view_all_attendance.php">View All Attendance Records</a></li>
            <li><a href="create_class.php">Create / Manage Classes</a></li>
            <li><a href="reports.php">Generate Attendance Reports</a></li>
        </ul>

    <?php elseif ($userRole === 'teacher'): ?>
        <!-- TEACHER DASHBOARD -->
        <p class="section-title">Teacher Options:</p>
        <ul>
            <li><a href="take_attendance.php">Take Attendance</a></li>
            <li><a href="view_my_classes.php">View My Classes</a></li>
            <li><a href="view_class_attendance.php">View Class Attendance</a></li>
            <li><a href="download_reports.php">Download Attendance Reports</a></li>
        </ul>

    <?php else: ?>
        <!-- STUDENT DASHBOARD -->
        <p class="section-title">Student Options:</p>
        <ul>
            <li><a href="my_attendance.php">View My Attendance</a></li>
            <li><a href="profile.php">View / Edit Profile</a></li>
            <li><a href="notifications.php">View Notifications</a></li>
        </ul>
    <?php endif; ?>

    <p style="margin-top:30px; font-size: 13px; color:#555;">
        ⚙️ Note: The above links like <code>take_attendance.php</code>, <code>my_attendance.php</code> etc.
        are separate pages you will create for your project functionality.
    </p>
</div>

</body>
</html>
