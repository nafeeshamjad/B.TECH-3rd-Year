<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="css/style.css"> <!-- your css -->
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>

<ul>
    <li><a href="view_students.php">View My Students</a></li>
    <li><a href="view_attendance.php">View Attendance</a></li>

    <!-- 👇 NEW: Face Attendance option -->
    <li><a href="mark_attendance.php">Start Face Attendance</a></li>

    <li><a href="logout.php">Logout</a></li>
</ul>

</body>
</html>

    <style>
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, #eaf2ff, #ffffff);
            display: flex;
        }

        /* -------------------- SIDEBAR -------------------- */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #0066ff, #003ecb);
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            position: fixed;
            box-shadow: 5px 0 25px rgba(0,0,0,0.15);
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 35px;
            font-size: 26px;
            font-weight: 600;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.25s ease;
            background: rgba(255,255,255,0.1);
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.25);
            transform: translateX(5px);
        }

        .menu-icon {
            margin-right: 15px;
            width: 22px;
            height: 22px;
        }

        a {
            text-decoration: none;
            color: white;
            font-size: 17px;
            font-weight: 500;
        }

        /* -------------------- MAIN CONTENT -------------------- */
        .main {
            margin-left: 280px;
            padding: 40px;
            width: calc(100% - 260px);
        }

        .header {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #222;
        }

        /* Glassmorphism card */
        .card {
            background: rgba(255,255,255,0.55);
            backdrop-filter: blur(8px);
            padding: 28px;
            border-radius: 18px;
            width: 450px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(10px);}
            to   {opacity: 1; transform: translateY(0);}
        }

        .welcome {
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 25px;
            color: #444;
        }

        .btn {
            width: 100%;
            background: #007bff;
            color: white;
            padding: 14px;
            margin-bottom: 15px;
            font-size: 17px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn:hover {
            background: #005ad4;
            transform: translateY(-3px);
            box-shadow: 0 5px 18px rgba(0, 91, 212, 0.3);
        }

        .btn-logout {
            background: #ff3b3b;
        }

        .btn-logout:hover {
            background: #d62828;
            box-shadow: 0 5px 15px rgba(255,60,60,0.3);
        }

        svg {
            width: 20px;
            height: 20px;
        }

        @media(max-width: 800px){
            .sidebar{
                display: none;
            }
            .main{
                margin: 0;
                width: 100%;
            }
            .card{
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- -------------------- SIDEBAR -------------------- -->

    <div class="sidebar">
        <h2>Teacher</h2>

        <a href="teacher_dashboard.php" class="menu-item">
            <svg class="menu-icon" fill="white"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
            Dashboard
        </a>

        <a href="mark_attendance.php" class="menu-item">
            <svg class="menu-icon" fill="white"><path d="M19 3H5c-1.1 0-2 .9-2 2v14l4-4h12c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
            Mark Attendance
        </a>

        <a href="view_attendance.php" class="menu-item">
            <svg class="menu-icon" fill="white"><path d="M3 5h18v2H3zm0 6h18v2H3zm0 6h18v2H3z"/></svg>
            View Attendance
        </a>

        <a href="logout.php" class="menu-item">
            <svg class="menu-icon" fill="white"><path d="M16 13v-2H7V8l-5 4 5 4v-3zM20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
            Logout
        </a>
    </div>

    <!-- -------------------- MAIN CONTENT -------------------- -->

    <div class="main">
        <div class="header">Teacher Dashboard</div>

        <div class="card">
            <div class="welcome">Welcome, Teacher 👋</div>

            <button class="btn" onclick="window.location='mark_attendance.php'">
                <svg fill="white"><path d="M19 3H5c-1.1 0-2 .9-2 2v14l4-4h12c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                Mark Attendance
            </button>

            <button class="btn" onclick="window.location='view_attendance.php'">
                <svg fill="white"><path d="M3 5h18v2H3zm0 6h18v2H3zm0 6h18v2H3z"/></svg>
                View Attendance
            </button>

            <button class="btn btn-logout" onclick="window.location='logout.php'">
                <svg fill="white"><path d="M16 13v-2H7V8l-5 4 5 4v-3zM20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                Logout
            </button>
        </div>
    </div>

</body>
</html>
