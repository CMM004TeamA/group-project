<?php
// Database connection
//error_reporting(E_ALL);
require_once "connect.php";
?>
<header class="mb-5 sticky-top">
        <nav class="container d-flex flex-nowrap navbar bg-light shadow-sm ">
            <div class="container">
                <a href="index.php" class="navbar-brand fw-bold text-purple fs-3">Team A</a>
                <form class="d-flex mx-auto w-50" id="searchForm" role="search">
                    <input id="searchInput" class="form-control" type="search" name="searchTerm" placeholder="Search for items..">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </form>
                <div>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!--- Display these if user is logged in based on role --->
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                            <a href="cart.php" class="btn btn-outline-purple text-nowrap">Cart <span id="cart-counter">0</span></a>
                            <a href="profile.php" class="btn btn-purple text-nowrap">Profile</a>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="View_Reservations.html" class="btn btn-purple text-nowrap">Search reservations</a>
                            <a href="profile_admin.php" class="btn btn-purple text-nowrap">Profile</a>
                        <?php endif; ?>
                    
                    <a href="logout.php" class="btn btn-outline-secondary text-nowrap">Log Out</a>
                    <?php else: ?>
                    <!--- Display these if user is not logged in --->
                    <button class="btn btn-outline-purple text-nowrap" onclick="window.location.href='login_form.html'">Log
                        In</button>
                    <button class="btn btn-purple text-nowrap" onclick="window.location.href='signup.html'">Sign Up</button>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <nav class="navbar">
            <div class="container">
                <ul class="navbar-nav me-auto mb-lg-0 d-flex flex-row">
                    <?php
                    // fetch grandparent category
                    $sql = "SELECT * FROM Categories WHERE parent_category_id IS NULL";
                    $stmt = $conn->query($sql);
                    $grandparent_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($grandparent_categories as $grandparent) {
                        echo '
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown' . $grandparent['category_id'] . '" role="button" 
                            data-bs-toggle="dropdown" aria-expanded="false">' . $grandparent['category_name'] . '</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown' . $grandparent['category_id'] . '">';
                        // fetch parent category
                        $sql = "SELECT*FROM Categories WHERE parent_category_id=?";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$grandparent["category_id"]]);
                        $parent_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


                        foreach ($parent_categories as $parent) {
                            echo '
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">' . $parent['category_name'] . '</a>
                                <ul class="dropdown-menu">';
                            // fetch child category
                            $sql = "SELECT*FROM Categories WHERE parent_category_id=?";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute([$parent["category_id"]]);
                            $child_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($child_categories as $child) {
                                echo '
                                <li>
                                    <a class="dropdown-item" href="category.php?id=' . $child['category_id'] . '">' . $child['category_name'] . '</a>
                                </li>';
                            }
                            echo '
                                </ul>
                            </li>';
                        }
                        echo '
                            </ul>
                        </li>';
                    }
                    ?>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="about.html">About</a>
                    </li>
                </ul>
            </div>
        </nav>

    </header>