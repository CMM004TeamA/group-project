<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Database connection
require_once "connect.php";

try {
    // Fetch cart items with their images
    $sql = "SELECT Items.*, (SELECT image_path FROM ItemImages WHERE ItemImages.item_id = Items.item_id LIMIT 1) AS image_path 
    FROM CartItems JOIN Items ON CartItems.item_id = Items.item_id WHERE CartItems.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <main class="container">
        <h1>Your Cart</h1>
        <?php if (empty($cartItems)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($cartItems as &$item): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="<?php echo $item['image_path']; ?>" class="card-img-top"
                                alt="<?php echo $item['title']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $item['title']; ?></h5>
                                <button class="btn btn-danger remove-from-cart"
                                    data-item-id="<?php echo $item['item_id']; ?>">Remove</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <button id="reserveAll" class="btn btn-purple mt-5">Reserve All</button>
    </main>
    <footer class="bg-secondary text-center fw-bold py-3">
        <p>&copy; 2025 CMM004 Team A. All rights reserved || Support Contact Email:
            <a href="mailto:teamacmm004@gmail.com" class="text-decoration-none text-purple">teamacmm004@gmail.com</a>
        </p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/cartAJAX.js"></script>
</body>

</html>