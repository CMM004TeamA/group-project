<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cmm004_teama_new";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection successful";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>