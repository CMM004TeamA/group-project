<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: loginForm.html");
    exit();
}

// Database connection
require_once "connect.php";

// Get user id
$user_id = $_SESSION["user_id"];
echo "User ID: " . $user_id . "<br>"; // Debugging

// Get item id
$item_id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
echo "Item ID: " . $item_id . "<br>"; // Debugging

// Fetch selected item
$sql = "SELECT*FROM Items WHERE item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$item_id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    die("Item not found. Item ID: " . $item_id); // Debugging
}

// Check if item is already reserved
echo "Item Status ID: " . $item['status_id'] . "<br>"; // Debugging

echo "Item Data: ";
print_r($item); // Debugging

if ($item['status_id'] != 1) {
    die("This item is not available. Status ID: " . $item['status_id']); // Debugging
}

// Update the item in the database to reserved status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST request received.<br>"; // Debugging

    try {
        // Begin transaction
        $conn->beginTransaction();

        // Update the item status
        $sql = "UPDATE Items SET status_id = 3 WHERE item_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$item_id]);

        // Insert into the reservation table
        $sql = "INSERT INTO Reservations (user_id, item_id, reservation_date) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id, $item_id]);

        // Commit transaction
        $conn->commit();

        // Redirect to confirmation page
        header("Location: reservationconfirmation.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollBack();
        die("An error occurred: " . $e->getMessage());
    }
}
?>