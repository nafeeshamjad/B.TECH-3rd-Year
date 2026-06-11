<?php
session_start();

// If not logged in OR not admin, send back to login
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get admin name from session (set in login.php)
$adminName = $_SESSION['user_name'];  // <-- IMPORTANT
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        /* ===== Global Styles ===== */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Poppins", Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top left, #c9ddff, #edf2ff 40%, #dde7ff);
        }

        /* ===== Card ===== */
        .dashboard-card {
            width: 90%;
            max-width: 520px;
            background: #ffffff;
            padding: 35px 40px 30px;
            border-radius: 18px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: floatIn 0.7s ease-out;
        }

        /* Decorative gradient strip at top */
        .dashboard-card::before {
            content: "";
            position: absolute;
            inset: 0;
            opacity: 0.08;
            background: linear-gradient(120deg, #2563eb, #4f46e5, #06b6d4);
            pointer-events: none;
        }

        .card-inner {
            position: relative; /* to be above ::before */
            z-index: 2;
        }

        /* ===== Header ===== */
        .title {
            font-size: 26px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 22px;
        }

        .emoji-wave {
            font-size: 26px;
            margin-left: 4px;
            animation: wave 1.8s infinite;
            display: inline-block;
            transform-origin: 70% 70%;
        }

        /* ===== Buttons Layout ===== */
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 10px;
        }

        .btn {
            border: none;
            outline: none;
            cursor: pointer;
            padding: 12px 18px;
            border-radius: 999px;
            font-size: 15px;
            font-weight: 500;
            letter-spacing: 0.01em;
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease, filter 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.35);
        }

        .btn-primary:hover {
            filter: brightness(1.05);
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.45);
        }

        .btn-outline {
            background: #eff6ff;
            color: #1e3a8a;
            border: 1px solid #bfdbfe;
        }

        .btn-outline:hover {
            background: #dbeafe;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(148, 163, 184, 0.35);
        }

        .btn-danger {
            background: #ef4444;
            color: #ffffff;
            box-shadow: 0 8px 18px rgba(220, 38, 38, 0.35);
            margin-top: 8px;
        }

        .btn-danger:hover {
            filter: brightness(1.05);
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(220, 38, 38, 0.45);
        }

        .btn span.icon {
            font-size: 17px;
        }

        /* Small footer text */
        .footer-text {
            margin-top: 18px;
            font-size: 11px;
            color: #9ca3af;
        }

        /* ===== Animations ===== */
        @keyframes floatIn {
            from {
                opacity: 0;
                transform: translateY(18px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes wave {
            0% { transform: rotate(0deg); }
            15% { transform: rotate(18deg); }
            30% { transform: rotate(-10deg); }
            45% { transform: rotate(15deg); }
            60% { transform: rotate(0deg); }
            100% { transform: rotate(0deg); }
        }

        /* Responsive tweaks */
        @media (max-width: 480px) {
            .dashboard-card {
                padding: 26px 20px 24px;
                border-radius: 14px;
            }
            .title {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>

<div class="dashboard-card">
    <div class="card-inner">
        <h1 class="title">
            Welcome Admin, <?php echo htmlspecialchars($adminName); ?>
            <span class="emoji-wave">👋</span>
        </h1>
        <p class="subtitle">Manage the entire attendance system from one place</p>

        <div class="btn-group">
            <button class="btn btn-primary" onclick="location.href='add_teacher.php'">
                <span class="icon">👨‍🏫</span> Add Teacher
            </button>

            <button class="btn btn-primary" onclick="location.href='add_student.php'">
                <span class="icon">👨‍🎓</span> Add Student
            </button>

            <button class="btn btn-outline" onclick="location.href='view_students.php'">
                <span class="icon">📋</span> View All Students
            </button>

            <button class="btn btn-outline" onclick="location.href='view_teachers.php'">
                <span class="icon">📚</span> View All Teachers
            </button>

            <button class="btn btn-outline" onclick="location.href='view_attendance.php'">
                <span class="icon">✅</span> View All Attendance
            </button>

            <button class="btn btn-danger" onclick="location.href='logout.php'">
                <span class="icon">🚪</span> Logout
            </button>
        </div>

        <p class="footer-text">Cloud Based Attendance System • Admin Panel</p>
    </div>
</div>

</body>
</html>