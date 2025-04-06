<?php
require 'session.php';
require 'connect.php';

// Sanitize and validate input
$username = trim($_POST['username']);
$email = trim($_POST['email']);

// Validate empty fields
if (empty($username) || empty($email)) {
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
if ($email == $_SESSION['user_email']) {
    $check_email = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $check_email->execute([$email]);
    
try {
    $update_stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE email = ?");
    $update_stmt->execute([$username, $email, $user_email]);
    
    //set session variables
    $_SESSION['user_name'] = $row['username'];
    $_SESSION['user_email'] = $row['email'];

    // $_SESSION['success_message'] = "Profile updated successfully!";
    echo "<script>alert('Profile updated successfully!Please log in again'); window.location.href='profile_admin.php'; </script>";
} catch (PDOException $e) {
    echo "<script>alert('Database Error: ". $e->getMessage() ."'); window.location.href='profile_admin.php'; </script>";
}
}

exit();
?>
