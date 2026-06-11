<?php
require 'config.php';
if(isset($_POST['submit'])){
  $student_id = $_POST['student_id'];
  $name = $_POST['name'];
  $class = $_POST['class'];
  if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0){
    $uploadDir = 'uploads/students/';
    if(!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $filename = $student_id . '_' . time() . '.' . $ext;
    $path = $uploadDir . $filename;
    move_uploaded_file($_FILES['photo']['tmp_name'], $path);

    $stmt = $pdo->prepare("INSERT INTO students (student_id, name, class, photo) VALUES (?, ?, ?, ?)");
    $stmt->execute([$student_id, $name, $class, $path]);

    // optionally insert into faces table for training
    $stmt2 = $pdo->prepare("INSERT INTO faces (student_id, image_path) VALUES (?, ?)");
    $stmt2->execute([$student_id, $path]);

    echo "Student added.";
  } else {
    echo "Upload error.";
  }
}
?>
