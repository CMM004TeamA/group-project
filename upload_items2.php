<?php
session_start();
include "connect.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: loginForm.html"); 
    exit();
}

$user_id = $_SESSION['user_id'];


 

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    try {
        // GET FORM DATA AFTER SUBMISSION
        $itemName = $_POST["ItemName"];
        $grandparent_category_id = $_POST['grandparent_category']; 
        $parent_category_id = $_POST['parent_category']; 
        $child_category_id = $_POST['child_category']; 
        $size = $_POST['size']; // This may be empty
        $condition = $_POST["condition"];
        $description = $_POST["description"];
        $user_id = $_SESSION['user_id']; 
        $status_id = 1; 

        // Validate form data
        if (!$itemName || !$grandparent_category_id || !$parent_category_id || !$child_category_id || !$condition || !$description) {
            throw new Exception("All fields are required except size.");
        }

        // Get sizeID (if size is provided)
        $size_id = null;
        if (!empty($_POST['size'])) {
            $size_id = $_POST['size']; 
            echo "Size ID: " . $size_id . "<br>"; 
        }

        // Get conditionID
        $conditionresult = $conn->prepare("SELECT condition_id FROM conditions WHERE condition_name = :condition_name");
        $conditionresult->execute(['condition_name' => $condition]);
        $condition_id = $conditionresult->fetchColumn();
        if (!$condition_id)
            throw new Exception("Invalid Condition.");

        // Debugging: Print all parameters
        echo "<pre>";
        print_r([
            ':user_id' => $user_id,
            ':category_id' => $child_category_id, // Use child_category_id as the category_id
            ':status_id' => $status_id,
            ':size_id' => $size_id,
            ':condition_id' => $condition_id,
            ':title' => $itemName,
            ':description' => $description
        ]);
        echo "</pre>";

        // INSERT INTO ITEMS TABLE
        $itemresult = $conn->prepare("INSERT INTO items(user_id, category_id, status_id, size_id, condition_id, title, description)
        VALUES(:user_id, :category_id, :status_id, :size_id, :condition_id, :title, :description)");
        $itemresult->execute([
            ':user_id' => $user_id,
            ':category_id' => $child_category_id, // Use child_category_id as the category_id
            ':status_id' => $status_id,
            ':size_id' => $size_id, // This can be null
            ':condition_id' => $condition_id,
            ':title' => $itemName,
            ':description' => $description
        ]);

        // Get last inserted item_id
        $item_id = $conn->lastInsertId();

        // HANDLE FILE UPLOAD
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("No image uploaded.");
        }

        // Validate file type and size
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            throw new Exception("Only JPEG, PNG, and GIF images are allowed.");
        }

        if ($_FILES['image']['size'] > $maxFileSize) {
            throw new Exception("File size must be less than 5MB.");
        }

        // Specify upload directory
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
        }

        if (!is_writable($uploadDir)) {
            throw new Exception("Upload directory is not writable.");
        }

        // Generate a unique file name
        $fileName = basename($_FILES['image']['name']);
        $filePath = $uploadDir . uniqid() . '_' . $fileName;

        // Move the uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            throw new Exception("Failed to save image.");
        }

        // INSERT image path INTO itemimages Table
        $imageresult = $conn->prepare("INSERT INTO itemimages(item_id, image_path) VALUES (:item_id, :image_path)");
        $imageresult->execute([':item_id' => $item_id, ':image_path' => $filePath]);

        // Success response
        $_SESSION['success'] = 'Item uploaded successfully!';
        header("Location: upload_form.php");
        exit();
        
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>