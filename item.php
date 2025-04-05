<?php
session_start();

//Check if the user is logged in
if (isset($_SESSION['user_id'])) {

} else {

}

// Database connection
error_reporting(E_ALL);
require_once "connect.php";

//get the item id
$item_id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

//fetch select item
$sql = "SELECT Items.*, Sizes.size_name, Conditions.condition_name, Statuses.status_name, Users.username
        FROM Items
        LEFT JOIN Sizes ON Items.size_id = Sizes.size_id
        LEFT JOIN Conditions ON Items.condition_id = Conditions.condition_id
        LEFT JOIN Statuses ON Items.status_id = Statuses.status_id
        LEFT JOIN Users ON Items.user_id = Users.user_id
        WHERE Items.item_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$item_id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    die("Item not found.");
}
//get item's image(s)
$sql = "SELECT image_path FROM ItemImages WHERE item_id=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$item['item_id']]);
$image = $stmt->fetch(PDO::FETCH_ASSOC);
$item['image_path'] = $image ? $image['image_path'] : null;

// fetch select child category
$sql = "SELECT * FROM Categories WHERE category_id=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$item['category_id']]);
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
$stmt->execute([$item['category_id']]);
$path = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$path) {
    die("Invalid category path.");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $item['title']; ?> TeamA</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <?php include 'header.php'; ?>

    <main class="container ">
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo $item['image_path']; ?>" class="img-fluid" alt="<?php echo $item['title']; ?>">
            </div>
            <div class="col-md-6">
                <h1><?php echo $item['title']; ?></h1>
                <p>Category: <?php echo $path['child_name']; ?></p>
                <p>Size: <?php echo $item['size_name']; ?></p>
                <p>Condition: <?php echo $item['condition_name']; ?></p>
                <p>Status: <?php echo $item['status_name']; ?></p>
                <p>Posted by: <?php echo $item['username']; ?></p>
                <p><?php echo $item['description']; ?></p>

                <div>
                    <button class="btn btn-purple" onclick="reserveItem(<?php echo $item['item_id']; ?>)">Reserve
                        Now</button>
                    <button id="addToCart" class="btn btn-outline-purple add-to-cart" data-item-id="<?php echo $item['item_id']; ?>">Add to
                        Cart</button>
                </div>


            </div>

        </div>
    </main>
    <footer class="mt-5">
        <p class="text-center fw-bold">(c) 2025 CMM004 Team A  ||  Support Contact Email: 
            <a href="mailto:teamacmm004@gmail.com" class="text-decoration-none text-purple">teamacmm004@gmail.com</a> </p>
    </footer>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/nav-dropdown.js"></script>
    <script>
        function reserveItem(itemId) {
            if (confirm("Are you sure you want to reserve this item?")) {
                let form = document.createElement("form");
                form.method = "POST";
                form.action = `reservenow.php?id=${itemId}`;
                document.body.appendChild(form);
                form.submit();
            }
        }   
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/cartAJAX.js"></script>

</body>
<!-- Footer -->
<footer class="bg-secondary text-center fw-bold py-3">
  <p>&copy; 2025 CMM004 Team A. All rights reserved ||  Support Contact Email: 
  <a href="mailto:teamacmm004@gmail.com" class="text-decoration-none text-purple">teamacmm004@gmail.com</a> </p>
</footer>

</html>