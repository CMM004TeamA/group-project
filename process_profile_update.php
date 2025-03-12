<?php
session_start();
require 'CMM004_dbconfig.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('User not logged In'); window.location.href='login_form.html';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Error: Invalid request method!'); window.location.href='javascript:history.go(-1)';</script>";
    exit();
}

// Get current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$current_user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$current_user) {
    echo "<script>alert('User not found! Please Register/login'); window.location.href='login_form.html';</script>";
    exit();
}

// Sanitize and validate input
$firstname = trim($_POST['firstname']);
$lastname = trim($_POST['lastname']);
$email = trim($_POST['email']);

// Validate empty fields
if (empty($firstname) || empty($lastname) || empty($email)) {
    echo "<script>alert('Warning: All fields required!'); window.location.href='javascript:history.go(-1)';</script>";
    exit();
}


// Validate email
if (empty($email)) {
    $errors[] = "Email is required!";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format!'); window.location.href='javascript:history.go(-1)';</script>";
}


// Check for existing email
if ($email !== $current_user['email']) {
    $check_email = $pdo->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
    $check_email->execute([$email, $user_id]);
    if ($check_email->fetch()) {
        echo "<script>alert('Email already registered!'); window.location.href='javascript:history.go(-1)';</script>";
        exit();
    }
}

// Password handling
// if (empty($submitted_password)) {
//     // Keep current password if field is empty
//     $password_hash = $current_user['password_hash'];
// } else {
//     // Hash new password
//     $password_hash = password_hash($submitted_password, PASSWORD_DEFAULT);
// }

// Update database if no errors
try {
    $update_stmt = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE user_id = ?");
    $update_stmt->execute([$firstname, $lastname, $email, $user_id]);
    
    // $_SESSION['success_message'] = "Profile updated successfully!";
    echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php'; </script>";
} catch (PDOException $e) {
    echo "<script>alert('Database Error: ". $e->getMessage() ."'); window.location.href='profile.php'; </script>";
}


exit();
?>