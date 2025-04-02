<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); // Display errors

session_start();


// Database connection

require_once "connect.php";

// Initialize response array
$response = ['success' => false, 'message' => '', 'cartCount' => 0];

// Ensure user is logged in
// if (!isset($_SESSION["user_id"])) {
//     header("Location: loginForm.html");
//     exit();
// }

if (!isset($_SESSION["user_id"])) {
    $response['message'] = "You must be logged in to add items to the cart.";
    echo json_encode($response);
    exit();
}


// Get the item ID from the POST request
if (!isset($_POST['id'])) {
    $response['message'] = "Item ID is missing.";
    echo json_encode($response);
    exit();
}

$item_id = intval($_POST['id']);
$user_id = $_SESSION['user_id'];

// Validate the item ID
if ($item_id <= 0) {
    $response['message'] = "Invalid item ID.";
    echo json_encode($response);
    exit();
}

try {
    // Check if the item exists in the Items table
    $sql = "SELECT * FROM Items WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$item_id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        $response['message'] = "Item not found.";
        echo json_encode($response);
        exit();
    }

    // Check if the item is already in cart
    $sql = "SELECT * FROM CartItems WHERE user_id = ? AND item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $item_id]);
    $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingItem) {
        $response['message'] = "Item is already in your cart.";
        echo json_encode($response);
        exit();
    }

    // Update the item's status_id to 2 (In Cart)
    $sql = "UPDATE Items SET status_id = 2 WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$item_id]);
    // Insert the item into the cart
    $sql = "INSERT INTO CartItems (user_id, item_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $item_id]);

    // Get the updated cart count
    $sql = "SELECT COUNT(*) AS cartCount FROM CartItems WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    $cartCount = $stmt->fetch(PDO::FETCH_ASSOC)['cartCount'];

    // Set success response
    $response['success'] = true;
    $response['message'] = "Item added to cart successfully!";
    $response['cartCount'] = $cartCount;
    echo json_encode($response);
    exit();
} catch (PDOException $e) {
    $response['message'] = "Database error: " . $e->getMessage();
    echo json_encode($response);
    exit();
}