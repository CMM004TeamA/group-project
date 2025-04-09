<?php
session_start();

//Check if the user is logged in
if (isset($_SESSION['user_id'])) {

} else {

}

// Database connection
//error_reporting(E_ALL);
require_once "connect.php";

// get category id
$category_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// fetch select child category
$sql = "SELECT * FROM Categories WHERE category_id=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$category_id]);
$selected_category = $stmt->fetch(PDO::FETCH_ASSOC);

//fetch full path for select child category
$sql = "SELECT C1.category_id AS grandparent_id,C1.category_name AS grandparent_name,
C2.category_id AS parent_id,C2.category_name AS parent_name,
C3.category_id AS child_id,C3.category_name AS child_name
FROM Categories C1
LEFT JOIN Categories C2 ON C1.category_id=C2.parent_category_id
LEFT JOIN Categories C3 ON C2.category_id=C3.parent_category_id
WHERE C3.category_id=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$category_id]);
$path = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$path) {
    die("Invalid category path.");
}

// Fetch items and their images
$sql = "SELECT Items.*, ItemImages.image_path FROM Items LEFT JOIN ItemImages ON Items.item_id = ItemImages.item_id 
        WHERE Items.category_id = ? AND Items.status_id = 1 GROUP BY Items.item_id 
        ORDER BY Items.date_added DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$category_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $selected_category['category_name']; ?> - TeamA</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <?php include 'header.php'; ?>

    <main class="container ">
        <h1 class="text-center mb-4"><?php echo $selected_category['category_name']; ?></h1>
        <div class="row g-4">
            <?php
            foreach ($items as $item) {
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
   <!-- Footer -->
<footer class="bg-secondary text-center fw-bold py-3">
  <p>&copy; 2025 CMM004 Team A. All rights reserved ||  Support Contact Email: 
  <a href="mailto:teamacmm004@gmail.com" class="text-decoration-none text-purple">teamacmm004@gmail.com</a> </p>
</footer>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/nav-dropdown.js"></script>
    <script src="assets/js/search.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/cartAJAX.js"></script>
</body>

</html>