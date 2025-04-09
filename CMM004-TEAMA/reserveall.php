<?php
//error_reporting(E_ALL);
session_start();

// Initialize response array
$response = ['success' => false, 'message' => ''];

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    $response['message'] = "You must be logged in to reserve items.";
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection
require_once "connect.php";

try {
    // Fetch all items in the user's cart
    $sql = "SELECT item_id FROM CartItems WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cartItems)) {
        $response['message'] = "Your cart is empty.";
        echo json_encode($response);
        exit();
    }

    // Reserve all items in the cart
    foreach ($cartItems as $item) {
        $item_id = $item['item_id'];

        // Update the item's status_id to 3 (Reserved)
        $sql = "UPDATE Items SET status_id = 3 WHERE item_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$item_id]);
    }

    // Remove all items from the cart
    $sql = "DELETE FROM CartItems WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);

    // Set success response
    $response['success'] = true;
    $response['message'] = "All items reserved successfully!";
    echo json_encode($response);
    exit();
} catch (PDOException $e) {
    $response['message'] = "Database error: " . $e->getMessage();
    echo json_encode($response);
    exit();
}