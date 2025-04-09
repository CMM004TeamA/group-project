<?php
require 'session.php';
require 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" link href="assets/css/style_updateprofile.css">
    <title>Profile Page</title>
</head>
<body>
    <div class="profile-container">
        <h3>Update Profile</h3>

        <form id="profile-form" action="process_profile_update_admin.php" method="POST" onsubmit="return confirm('Are you sure you want to update your profile?');">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required placeholder="Enter username">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" required placeholder="Enter email">
            </div>

            <div class="button-group">
                <button type="submit" id="edit-btn">Save</button>
            </div>
        </form>
    </div>
</body>
</html>