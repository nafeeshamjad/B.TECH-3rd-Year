<?php
session_start();
require 'config.php';

// Only admin can access this page
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data safely
    $name     = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $email    = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = "teacher";

    if ($name === '' || $email === '' || $password === '') {
        $message = "All fields are required.";
    } else {
        // Hash password (recommended)
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO users (name, email, password, role) 
                  VALUES ('$name', '$email', '$hashedPassword', '$role')";

        if (mysqli_query($conn, $query)) {
            $message = "Teacher Added Successfully!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Teacher</title>
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
            background: radial-gradient(circle at top left, #c9ddff, #edf2ff 40%, #dde7ff);
        }

        /* ===== Card ===== */
        .add-teacher-card {
            width: 90%;
            max-width: 520px;
            background: #ffffff;
            padding: 35px 40px 32px;
            border-radius: 18px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
            position: relative;
            overflow: hidden;
        }

        .add-teacher-card::before {
            content: "";
            position: absolute;
            inset: 0;
            opacity: 0.08;
            background: linear-gradient(120deg, #2563eb, #4f46e5, #06b6d4);
            pointer-events: none;
        }

        .add-teacher-inner {
            position: relative;
            z-index: 2;
        }

        /* ===== Heading ===== */
        .add-teacher-title {
            font-size: 26px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 24px;
        }

        /* ===== Message ===== */
        .message {
            margin-bottom: 14px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 14px;
        }

        .message.success {
            background: #ecfdf3;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .message.error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        /* ===== Form Inputs ===== */
        .add-teacher-form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .add-teacher-form input[type="text"],
        .add-teacher-form input[type="email"],
        .add-teacher-form input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            outline: none;
            transition: border 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            background: #f9fafb;
        }

        .add-teacher-form input::placeholder {
            color: #9ca3af;
        }

        .add-teacher-form input:focus {
            border-color: #2563eb;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
        }

        /* ===== Buttons ===== */
        .btn-row {
            margin-top: 18px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
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
            width: 100%;
            transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.35);
        }

        .btn-primary:hover {
            filter: brightness(1.05);
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.45);
        }

        .btn-secondary {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            max-width: 220px;
        }

        .btn-secondary:hover {
            background: #dbeafe;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(148, 163, 184, 0.35);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .add-teacher-card {
                padding: 26px 20px 24px;
                border-radius: 14px;
            }
            .add-teacher-title {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
<div class="add-teacher-card">
    <div class="add-teacher-inner">
        <h1 class="add-teacher-title">Add Teacher</h1>

        <?php if ($message !== ""): ?>
            <div class="message <?php echo (strpos($message, 'Successfully') !== false) ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="post" class="add-teacher-form">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <div class="btn-row">
                <button type="submit" class="btn btn-primary">Add Teacher</button>
                <button type="button" onclick="history.back()" class="btn btn-secondary">Back</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
