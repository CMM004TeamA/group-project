<?php
require 'session.php';
require 'connect.php';


$user_email = $_SESSION['user_email'] ?? '';

if (!$user_email) {
    echo "<script>alert('Unauthorized access.'); window.location.href='index.php';</script>";
    exit();
}


$old_password = trim($_POST['old_password']);
$new_password = trim($_POST['new_password']);
$repeat_new_password = trim($_POST['repeat_new_password']);


if (empty($old_password) || empty($new_password) || empty($repeat_new_password)) {
    echo "<script>alert('All fields are required!'); window.history.back();</script>";
    exit();
}


if (strlen($new_password) < 8) {
    echo "<script>alert('New password must be at least 8 characters!'); window.history.back();</script>";
}

if ($new_password !== $repeat_new_password) {
    echo "<script>alert('New passwords do not match!'); window.history.back();</script>";
}

else {
// Assisted with AI
$stmt = $conn->prepare("SELECT password_hash FROM users WHERE email = ?");
$stmt->execute([$user_email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($old_password, $user['password_hash'])) {
    echo "<script>alert('Old password is incorrect!'); window.history.back();</script>";
    exit();
}

$new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);


$update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
$update_stmt->execute([$new_password_hash, $user_email]);

echo "<script>alert('Password updated successfully!'); window.location.href='profile.php';</script>";
exit();
}
?>