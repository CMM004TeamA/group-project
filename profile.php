<?php
session_start();
require 'CMM004_dbconfig.php';

// Dummy Session Id
 $_SESSION['user_id'] = 2;
//unset($_SESSION['user_id']);

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to view your profile!'); window.location.href='login_form.html';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<script>alert('User not found! Please Register/login'); window.location.href='login_form.html';</script>";
    die;
}

$firstName = $user['firstname'];
$lastName = $user['lastname'];
$email = $user['email'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile Page</title>
  <!-- Bootstrap CSS via CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
  <!-- Font Awesome CSS via CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark text-center d-flex justify-content-center">
  <a class="navbar-brand text-capitalize" href="#">My Profile</a>
</nav>

<div class="container mt-4">
    <section style="background-color: #fff;">
        <div class="container pb-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <!-- <li class="breadcrumb-item"><a href="#">Home</a></li> -->
                            <li class="breadcrumb-item active text-capitalize" aria-current="page">Hello, <?php echo $firstName; ?></li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 h-full">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <img src="https://static.vecteezy.com/system/resources/thumbnails/014/488/456/small/hands-giving-hearts-to-each-other-helping-the-poor-by-donating-items-to-charity-png.png" alt="avatar"
                            class="rounded-circle img-fluid" style="width: 150px;">
                            <p class="my-3 text-sm text-capitalize"><mark><?php echo $firstName . ' ' . $lastName; ?></mark></p>
                            <p class="text-muted mb-1">Contact</p>
                            <p class="text-muted mb-4"><?php echo $email; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Firstname</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0 text-capitalize"><?php echo $firstName; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Lastname</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0"><?php echo $lastName; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Gender</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">Not Specified</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Email</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0"><?php echo $email; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <a href="./edit_profile.php" class="col-sm-12">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editProfileModal">
                                        <i class="fas fa-edit"></i> Edit Profile
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
  <p>&copy; 2023 Donation Website. All rights reserved.</p>
</footer>

<!-- Bootstrap JS (Popper + Bootstrap) via CDN -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>