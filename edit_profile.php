<?php
session_start();
require 'CMM004_dbconfig.php';

// Dummy Session Id
// $_SESSION['user_id'] = 1;
// unset($_SESSION['user_id']);

// // Ensure the user is logged in
// if (!isset($_SESSION['user_id'])) {
//     echo "<script>alert('Please Login First'); window.location.href='login_form.html';</script>";
//     exit();
// }

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // die("User not found! Please check your database.");
    echo "<script>alert('User not registered/logged In'); window.location.href='login_form.html';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" link href="style_updateprofile.css">
    <title>Profile Page</title>
</head>
<body>
    <div class="profile-container">
        <h3>Update Profile</h3>

        <form id="profile-form" action="process_profile_update.php" method="POST" onsubmit="return confirm('Are you sure you want to update your profile?');">
            <div class="form-group">
                <label for="firstname">Firstname:</label>
                <input type="text" id="firstname" name="firstname" required placeholder="Enter firstname">
            </div>
            <div class="form-group">
                <label for="lastname">Lastname:</label>
                <input type="text" id="lastname" name="lastname" required placeholder="Enter lastname">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>

            <div class="button-group">
                <button type="submit" id="edit-btn">Save</button>
            </div>
        </form>
    </div>
</body>
</html>