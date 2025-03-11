<?php
// start session
session_start();
//ensure user is logged in
if(!isset($_SESSION["user_id"])){
    header("Location: loginForm.html");
    exit();
}
// Database connection
error_reporting(E_ALL);
require_once "connect.php";


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmation - Team A</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="mb-5 sticky-top">
        <nav class="navbar bg-light shadow-sm ">
            <div class="container">
                <a href="homepage.php" class="navbar-brand fw-bold text-purple fs-3">Team A</a>
                <form class="d-flex mx-auto w-50">
                    <input class="form-control" type="search" placeholder="Search for items..">
                    <button class="btn btn-outline-secondary" type="button" onclick="searchItems()">Search</button>
                </form>       

            </div>
        </nav>
   

    </header>
    <main class ="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>Reservation Confirmed</h1>
                <p>Thank you for using our website!</p>
                
            </div>

        </div>
    </main>
    <footer>
        <p>(c) 2025 CMM004 Team A</p>
    </footer>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>