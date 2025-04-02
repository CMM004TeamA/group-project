<?php
session_start();

//Check if the user is logged in
if (isset($_SESSION['user_id'])) {

} else {

}

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

    <?php include 'header.php'; ?>

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

        <div id="searchResultsSection" class="container mt-5" style="display: none;">
            <h2 class="text-center mb-4">Search Results</h2>
            <div id="itemContainer" class="row g-4"></div>
        </div>
    </main>

    <footer class="mt-5">
        <p class="text-center fw-bold">(c) 2025 CMM004 Team A  ||  Support Contact Email: teamacmm004@gmail.com </p>
    </footer>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/nav-dropdown.js"></script>
    <script src="assets/js/search.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/cartAJAX.js"></script>
</body>

</html>