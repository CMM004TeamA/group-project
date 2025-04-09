<?php
require 'session.php';
require 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <header class="mb-5">
        <nav class="navbar bg-light shadow-sm ">
            <div class="container">
                <a href="index.php" class="navbar-brand fw-bold text-purple fs-3">Team A</a>
                <form class="d-flex mx-auto w-50">
                    <a class="navbar-brand text-capitalize" href="#">My Profile</a>
                </form>

                <div>
                    <a href="admin_view_items.php" class="btn btn-outline-purple">View Items</a>
                    <a href="logout.php" class="btn btn-purple">Sign Out</a>
                </div>
            </div>
        </nav>
    </header>

    <body>


        <div class="container mt-4">
            <section style="background-color: #fff;">
                <div class="container pb-5">
                    <div class="row">
                        <div class="col">
                            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                                <ol class="breadcrumb mb-0">
                                    <!-- <li class="breadcrumb-item"><a href="#">Home</a></li> -->
                                    <li class="breadcrumb-item active text-capitalize" aria-current="page">Hello,
                                        <?php echo $username ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 h-full">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    <img src="https://static.vecteezy.com/system/resources/thumbnails/014/488/456/small/hands-giving-hearts-to-each-other-helping-the-poor-by-donating-items-to-charity-png.png"
                                        alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                                    <p class="my-3 text-sm text-capitalize"><mark><?php echo $username; ?></mark></p>
                                    <p class="text-muted mb-1">Contact:</p>
                                    <p class="text-muted mb-4"><?php echo $user_email; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">User Name</p>
                                        </div>
                                        <div class="col-sm-9">
                                            <p class="text-muted mb-0 text-capitalize"><?php echo $username; ?></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">Email</p>
                                        </div>
                                        <div class="col-sm-9">
                                            <p class="text-muted mb-0"><?php echo $user_email; ?></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row justify-content-between">
                                        <div class="col-auto">
                                            <a href="edit_profile_admin.php">
                                                <button type="button" class="btn btn-primary">
                                                    <i class="fas fa-edit"></i> Edit Profile
                                                </button>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <a href="password_form.php">
                                                <button type="button" class="btn btn-primary">
                                                    <i class="fas fa-edit"></i> Change Password
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>


        <!-- Footer -->
        <footer class="bg-secondary text-center fw-bold py-3">
            <p>&copy; 2025 CMM004 Team A. All rights reserved || Support Contact Email:
                <a href="mailto:teamacmm004@gmail.com"
                    class="text-decoration-none text-purple">teamacmm004@gmail.com</a>
            </p>
        </footer>

        <!-- Bootstrap JS (Popper + Bootstrap) via CDN -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>