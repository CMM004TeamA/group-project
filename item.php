<?php
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
$sql="SELECT * FROM Categories WHERE category_id=?";
$stmt=$conn->prepare($sql);
$stmt->execute([$item['category_id']]);
$selected_category=$stmt->fetch(PDO::FETCH_ASSOC);

//fetch full path for select child category
$sql= "SELECT C1.category_id AS grandparent_id,C1.category_name AS grandparent_name,
C2.category_id AS parent_id,C2.category_name AS parent_name,
C3.category_id AS child_id,C3.category_name AS child_name
FROM Categories C1
LEFT JOIN Categories C2 ON C1.category_id=C2.parent_category_id
LEFT JOIN Categories C3 ON C2.category_id=C3.parent_category_id
WHERE C3.category_id=?";
$stmt=$conn->prepare($sql);
$stmt->execute([$item['category_id']]);
$path=$stmt->fetch(PDO::FETCH_ASSOC);

if(!$path){
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

    <header class="mb-5 sticky-top">
        <nav class="navbar bg-light shadow-sm ">
            <div class="container">
                <a href="homepage.php" class="navbar-brand fw-bold text-purple fs-3">Team A</a>
                <form class="d-flex mx-auto w-50">
                    <input class="form-control" type="search" placeholder="Search for items..">
                    <button class="btn btn-outline-secondary" type="button" onclick="searchItems()">Search</button>
                </form>

                <div>
                    <button class="btn btn-outline-purple">Log In</button>
                    <button class="btn btn-purple">Sign Up</button>
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
                    <button class="btn btn-purple" onclick="reserveItem(<?php echo $item['item_id']; ?>)">Reserve Now</button>
                    <button class="btn btn-outline-purple" onclick="addToCart(<?php echo $item['item_id']; ?>)">Add to Cart</button>
                </div>

            
            </div>

        </div>
    </main>
    <footer>
        <p>(c) 2025 CMM004 Team A</p>
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

    function addToCart(itemId) {
        if (confirm("Add this item to your cart?")) {
            window.location.href = `add_to_cart.php?id=${itemId}`;
        }
    }
</script>
</body>

</html>