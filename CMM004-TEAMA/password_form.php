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
     <title>Password Page</title>
 </head>
 <body>
     <div class="profile-container">
         <h3>Change Profile</h3>
 
         <form id="profile-form" action="change_password.php" method="POST" onsubmit="return confirm('Are you sure you want to update your password?');">
             <div class="form-group">
                 <label for="old_password">Old password:</label>
                 <input type="password" id="old_password" name="old_password" required placeholder="Old password">
             </div>
             <div class="form-group">
                 <label for="new_password">New password:</label>
                 <input type="password" id="new_password" name="new_password" required placeholder="New Password">
             </div>
 
             <div class="form-group">
                 <label for="repeat_new_password">New password:</label>
                 <input type="password" id="repeat_new_password" name="repeat_new_password" required placeholder="Repeat New Password">
             </div>
 
             <div class="button-group">
                 <button type="submit" id="edit-btn">Save</button>
             </div>
         </form>
     </div>
 </body>
 </html>