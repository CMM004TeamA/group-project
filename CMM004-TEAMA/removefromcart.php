<?php
session_start();

// Initialize response array
$response = ['success' => false, 'message' => '', 'cartCount' => 0];

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    $response['message'] = "You must be logged in to remove items from your cart.";
    echo json_encode($response);
    exit();
}

// Check if the item ID is provided
if (!isset($_POST['id'])) {
    $response['message'] = "Item ID is missing.";
    echo json_encode($response);
    exit();
}

$item_id = intval($_POST['id']); // Get the item ID from the POST request
$user_id = $_SESSION['user_id']; // Get the user ID from the session

// Validate the item ID
if ($item_id <= 0) {
    $response['message'] = "Invalid item ID.";
    echo json_encode($response);
    exit();
}

// Database connection
require_once "connect.php";

try {
    // Check if the item exists in the user's cart
    $sql = "SELECT * FROM CartItems WHERE user_id = ? AND item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $item_id]);
    $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cartItem) {
        // Item not found in the cart
        $response['message'] = "Item not found in your cart.";
        echo json_encode($response);
        exit();
    }

    // Remove the item from the cart
    $sql = "DELETE FROM CartItems WHERE user_id = ? AND item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $item_id]);

    // Update the item's status_id back to 1 (Available)
    $sql = "UPDATE Items SET status_id = 1 WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$item_id]);

    // Get the updated cart count
    $sql = "SELECT COUNT(*) AS cartCount FROM CartItems WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    $cartCount = $stmt->fetch(PDO::FETCH_ASSOC)['cartCount'];

    // Set success response
    $response['success'] = true;
    $response['message'] = "Item removed from cart successfully!";
    $response['cartCount'] = $cartCount;
    echo json_encode($response);
    exit();
} catch (PDOException $e) {
    $response['message'] = "Database error: " . $e->getMessage();
    echo json_encode($response);
    exit();
}