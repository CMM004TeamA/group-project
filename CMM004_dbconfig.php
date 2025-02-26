<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbDatabase = "CMM004_teamA";

try {
    $dataSourceName = "mysql:host=$dbHost;dbname=$dbDatabase;charset=utf8mb4";
    $pdo = new PDO($dataSourceName, $dbUser, $dbPassword);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    echo "Oops! Try again later";
    exit();
}
?>