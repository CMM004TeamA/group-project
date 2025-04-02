<?php
session_start();

// Initialize response array
$response = ['success' => false, 'message' => '', 'cartCount' => 0];

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    $response['message'] = "You must be logged in to view your cart.";
    echo json_encode($response);
    exit();
}

// Database connection
require_once "connect.php";

try {
    // Get the cart count for the user
    $sql = "SELECT COUNT(*) AS cartCount FROM CartItems WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $cartCount = $stmt->fetch(PDO::FETCH_ASSOC)['cartCount'];

    // Set success response
    $response['success'] = true;
    $response['cartCount'] = $cartCount;
    echo json_encode($response);
    exit();
} catch (PDOException $e) {
    $response['message'] = "Database error: " . $e->getMessage();
    echo json_encode($response);
    exit();
}