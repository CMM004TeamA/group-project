<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);

    if (empty($first_name) || empty($last_name) || empty($email)) {
        echo "All fields are required!";
        exit();
    }

    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
    if ($stmt->execute([$first_name, $email, $user_id])) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
    } else {
        echo "Error updating profile!";
    }
} else {
    echo "Invalid request!";
}
?>
