<?php
session_start();
require 'config.php';   // must create $conn = mysqli_connect(...)

// -------------------- Access Control (Teacher Only) --------------------
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

$statusMessage = "";
$statusType = ""; // 'success' or 'error'

// -------------------- Start Face Attendance (call Python) --------------------
if (isset($_POST['start_face'])) {

    $pythonUrl = "http://127.0.0.1:5000/start";

    // cURL to call Python Flask server
    $ch = curl_init($pythonUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // give enough time because Python waits until you press 'q'
    curl_setopt($ch, CURLOPT_TIMEOUT, 600);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        $statusMessage = "Could not connect to Python server. Make sure recognize_server.py is running.<br>cURL error: " . htmlspecialchars($error);
        $statusType = "error";
    } else {
        $statusMessage = "Python server response: " . htmlspecialchars($response);
        $statusType = "success";
    }

    curl_close($ch);
}

// -------------------- Manual Attendance Form Submit --------------------
if (isset($_POST['submit_attendance'])) {

    $student_id = $_POST['student_id'] ?? '';
    $status = $_POST['status'] ?? 'Present';
    $today = date('Y-m-d');

    if ($student_id == '') {
        $statusMessage = "Please select a student.";
        $statusType = "error";
    } else {
        // check if already marked today
        $checkSql = "SELECT * FROM attendance WHERE user_id = ? AND date = ?";
        $stmt = mysqli_prepare($conn, $checkSql);
        mysqli_stmt_bind_param($stmt, 'is', $student_id, $today);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $statusMessage = "Attendance already marked today for this student.";
            $statusType = "error";
        } else {
            $insertSql = "INSERT INTO attendance (user_id, date, time, status) VALUES (?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($conn, $insertSql);
            $timeNow = date('H:i:s');
            mysqli_stmt_bind_param($stmt2, 'isss', $student_id, $today, $timeNow, $status);

            if (mysqli_stmt_execute($stmt2)) {
                $statusMessage = "Attendance marked successfully.";
                $statusType = "success";
            } else {
                $statusMessage = "Error marking attendance: " . mysqli_error($conn);
                $statusType = "error";
            }
        }
    }
}

// -------------------- Load Students for Dropdown --------------------
$students = mysqli_query($conn, "SELECT id, name FROM users WHERE role = 'student'");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Face Attendance</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Face Attendance</h2>

<?php if (!empty($message)): ?>
    <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php endif; ?>

<form method="POST">
    <button type="submit" name="start_face">Start Face Attendance</button>
</form>

<p><a href="teacher_dashboard.php">⬅ Back to Dashboard</a></p>

</body>
</html>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f6ff;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 450px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            color: #444;
        }

        select, button {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 18px;
            border: 1px solid #bbb;
            border-radius: 6px;
            font-size: 15px;
        }

        button {
            background: #4a6cf7;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 17px;
            transition: 0.3s;
        }

        button:hover {
            background: #2f4fd1;
        }

        .msg {
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
        }

        .success {
            background: #d4edda;
            color: #0f5132;
            border: 1px solid #badbcc;
        }

        .error {
            background: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Mark Attendance</h2>

    <?php if (!empty($message)): ?>
        <div class="msg <?php echo (strpos($message, 'successfully') !== false) ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <label for="user_id">Select Student</label>
        <select name="user_id" id="user_id" required>
            <option value="">-- Choose Student --</option>
            <?php foreach ($students as $student): ?>
                <option value="<?php echo $student['id']; ?>">
                    <?php echo $student['id'] . " - " . htmlspecialchars($student['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="status">Attendance Status</label>
        <select name="status" id="status" required>
            <option value="present">Present</option>
            <option value="absent">Absent</option>
            <option value="late">Late</option>
        </select>

        <button type="submit">Mark Attendance</button>
    </form>
</div>

</body>
</html>
