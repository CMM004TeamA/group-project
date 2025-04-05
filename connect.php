<?php
$servername = "localhost";
$user_name = "root";
$password = "";
$dbname = "CMM004_teamA";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $user_name, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>