<?php
// Database connection
error_reporting(E_ALL);
require_once "connect.php";

//fetch the first twenty recently added items
$sql = "SELECT DISTINCT * FROM Items WHERE status_id=1 ORDER BY date_added DESC LIMIT 20";
$stmt = $conn->prepare($sql);
$stmt->execute();
$recent_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

//fetch their images
foreach ($recent_items as &$item) {
    $sql = "SELECT image_path FROM ItemImages WHERE item_id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$item["item_id"]]);
    $image = $stmt->fetch(PDO::FETCH_ASSOC);
    $item['image_path'] = $image['image_path']; // Add image_path to the item
}
//ensure items are not repeated
$uniqueItems = [];
foreach ($recent_items as $item) {
    $uniqueItems[$item['item_id']] = $item;
}
$recent_items = array_values($uniqueItems);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - Team A</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <header class="mb-5 sticky-top">
        <nav class="navbar bg-light shadow-sm ">
            <div class="container">
                <a href="homepage.php" class="navbar-brand fw-bold text-purple fs-3">Team A</a>
                <form class="d-flex mx-auto w-50">
                    <input id="searchInput" class="form-control" type="search" placeholder="Search for items..">
                    <button class="btn btn-outline-secondary" type="button" onclick="searchItems()">Search</button>
                </form>

                <div id="itemContainer" class="items-grid"></div>

                <div>
                    <button class="btn btn-outline-purple" onclick="window.location.href='loginForm.html'">Log
                        In</button>
                    <button class="btn btn-purple" onclick="window.location.href='signup.html'">Sign Up</button>
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
                        <a class="nav-link" href="#">About</a>
                    </li>
                </ul>
            </div>
        </nav>

    </header>

    <section class="position-relative mb-5 vh-50">
        <img src="assets/images/donation imaginary.webp" alt="Donate and Pick What You Need"
            class="img-fluid w-100 h-100 object-fit-cover">
        <div class="position-absolute top-50 start-50 translate-middle text-center text-purple">
            <h1 class="fw-bold">Donate and Pick What You Need</h1>
            <p>Help others by donating items and find what you need.</p>
            <a href="upload_form.php" class="btn btn-purple">Donate Now</a>
        </div>
    </section>

    <main class="container ">
        <h2 class="text-center mb-4">Recently Added Items</h2>
        <div class="row g-4">
            <?php
            foreach ($recent_items as $item) {
                echo '
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <a href="item.php?id=' . $item['item_id'] . '" class="text-decoration-none text-dark">
                        <img src="' . $item['image_path'] . '" class="card-img-top" alt="' . $item['title'] . '">
                        <div class="card-body">
                            <h5 class="card-title">' . $item['title'] . '</h5>
                        </div>
                    </a>
                </div>               
            </div>';
            }
            ?>
        </div>
    </main>

    <footer>
        <p>(c) 2025 CMM004 Team A</p>
    </footer>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/nav-dropdown.js"></script>
    <script src="assets/js/search.js"></script>
</body>

</html>