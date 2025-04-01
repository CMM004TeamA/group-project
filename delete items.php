<?php
session_start();
include("connect.php");
error_reporting(E_ALL);
ini_set('display_error', 1);

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(["error" => "Unauthorized access."]);
            exit();
        }
        if  (!isset($_GET['item_id']) || empty($_GET['item_id'])) {
            http_response_code(400);
            echo json_encode(["error" => "Item ID is required."]);
            exit();
    }
$item_id = trim($_GET['item_id']);

$dataSourceName = "mysql:host=$dbHost;dbname=$dbDatabase;";
$pdo = new PDO($dataSourceName, $dbUser, $dbPassword);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$SQL = "DELETE FROM items WHERE items_id = :item_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(["item_id" => $item_id]);

if ($stmt->rowCount()>0) {
    http_response_code(200);
    echo json_encode(["success" => "Item deleted successfully."]); 
}

$pdo = null;
    } catch (PDOException $exception) {
        http_response_code(500);
        echo json_encode(["error" => $exception->getMessage()]); 
    } 
} else {
        http_response_code(400);
        echo json_encode(["error" => "Only GET requests are supported"]); 
    }   
?>


<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" link href="assets/style.css">
        <title>Delete Items</title>
    </head>
    <body>
        <h2>Admin (Manage Items)</h2>
        <div class="container">
            <div class="item-header">
                <div class="column id">ID</div>
                <div class="column name">Name</div>
                <div class="column action">Action</div>
            </div>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="item-row">
                <div class="column id"><?php echo $row['id']; ?></div>
                <div class="column name"><?php echo $row['name']; ?></div>
                <div class="column action">Action</div> 
                <a href="admin_delete.php?delete_id=<?php echo $row['id']; ?>" class="delete-btn">Delete</a>
                </div>
                <?php } ?>     
        </div>
    