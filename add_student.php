<?php
session_start();
require 'config.php';

// Only admin can access this page
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = "student";

    $query = "INSERT INTO users (name, email, password, role) 
              VALUES ('$name', '$email', '$password', '$role')";

    if (mysqli_query($conn, $query)) {
        $message = "Student Added Successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
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
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at top left, #c9ddff, #edf2ff 45%, #dde7ff);
    }

    /* ===== Card ===== */
    .box {
        width: 90%;
        max-width: 520px;
        background: #ffffff;
        padding: 32px 38px 30px;
        border-radius: 18px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
        position: relative;
        overflow: hidden;
    }

    .box::before {
        content: "";
        position: absolute;
        inset: 0;
        opacity: 0.1;
        background: linear-gradient(120deg, #2563eb, #4f46e5, #06b6d4);
        pointer-events: none;
    }

    .box > * {
        position: relative;
        z-index: 2;
    }

    /* ===== Heading ===== */
    .box h2 {
        font-size: 26px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 22px;
        letter-spacing: 0.01em;
    }

    /* ===== Message ===== */
    .msg {
        margin-bottom: 14px;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 14px;
        text-align: center;
        background: #ecfdf3;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    /* ===== Form ===== */
    form {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 12px;
    }

    input[type="text"],
    input[type="file"] {
        width: 100%;
        padding: 11px 13px;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        outline: none;
        background: #f9fafb;
        transition: border 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, transform 0.1s ease;
    }

    input::placeholder {
        color: #9ca3af;
    }

    input[type="text"]:focus,
    input[type="file"]:focus {
        border-color: #2563eb;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
        transform: translateY(-1px);
    }

    /* Make file input look cleaner */
    input[type="file"] {
        padding: 7px 6px;
        background: #f3f4f6;
    }

    /* ===== Buttons ===== */
    button,
    .back {
        display: block;
        width: 100%;
        padding: 12px 18px;
        margin-top: 8px;
        border-radius: 999px;
        border: none;
        outline: none;
        font-size: 15px;
        font-weight: 500;
        letter-spacing: 0.01em;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease;
    }

    /* Primary button – Add Student */
    button {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        box-shadow: 0 8px 18px rgba(37, 99, 235, 0.35);
    }

    button:hover {
        filter: brightness(1.05);
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.45);
    }

    /* Secondary button – Back */
    .back {
        margin-top: 16px;
        max-width: 220px;
        margin-left: auto;
        margin-right: auto;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        box-shadow: 0 5px 14px rgba(148, 163, 184, 0.25);
    }

    .back:hover {
        background: #dbeafe;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(148, 163, 184, 0.35);
    }

    /* ===== Responsive ===== */
    @media (max-width: 480px) {
        .box {
            padding: 24px 20px 22px;
            border-radius: 14px;
        }
        .box h2 {
            font-size: 22px;
        }
    }
</style>

</head>
<body>

<div class="box">
    <h2>Add Student</h2>

    <?php if ($message != "") echo "<p class='msg'>$message</p>"; ?>

    <form action="add_student.php" method="post" enctype="multipart/form-data">
          <input type="text" name="student_id" required placeholder="Roll No">
          <input type="text" name="name" required placeholder="Name">
          <input type="text" name="class" placeholder="Class">
          <input type="file" name="photo" accept="image/*" required>
          <button type="submit" name="submit">Add Student</button>

    </form>

    <a class="back" href="admin_dashboard.php">Back</a>
</div>

</body>
</html>
