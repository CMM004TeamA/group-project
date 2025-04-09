<?php
header("Content-Type: application/json"); //return JSON
session_start();
// Database connection
//error_reporting(E_ALL);
require_once "connect.php";

// Get the search term from the request
$searchTerm = isset($_GET["searchTerm"]) ? trim($_GET["searchTerm"]) : "";

try {

    error_log("Search term received: " . $searchTerm);
   
    // Fetch item and images
    $sql = "SELECT Items.*, ItemImages.image_path FROM Items LEFT JOIN ItemImages ON Items.item_id = ItemImages.item_id 
        WHERE Items.status_id=1 AND (Items.title LIKE ? OR Items.description LIKE ?) GROUP BY Items.item_id";
        $stmt = $conn->prepare($sql);
        $searchPattern = "%" . $searchTerm . "%";
        $stmt->execute([$searchPattern, $searchPattern]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($items);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([]);
}
?>