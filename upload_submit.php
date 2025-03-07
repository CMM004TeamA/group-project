<?php
session_start();
include "CMM004_dbconfig.php";
// Database configuration


// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    try {
            // Fetch all conditions for the dropdown.
            // This is used when you need to display multiple conditions from the database in a dropdown list.
            // It allows you to show all condition name options and use the corresponding condition_id when a user selects one.

        $conditionsStmt = $pdo->prepare("SELECT condition_id, condition_name FROM conditions");
        $conditionsStmt->execute();
        $conditions = $conditionsStmt->fetchAll(PDO::FETCH_ASSOC);
             
        // GET FORM DATA AFTER SUBMISSION
        $itemName = $_POST["ItemName"];
        $itemCategory = $_POST["ItemCategory"];
        $size = $_POST["size"];
        $condition = $_POST["condition"];
        $description = $_POST["description"];

         // Validate form data
         if (!$itemName || !$itemCategory || !$size || !$condition || !$description) {
            throw new Exception("All fields are required.");
        }

        // Looking up foreign keys with their IDs
        
        // Get categoryID
        $categoryresult = $pdo->prepare("SELECT category_id FROM categories WHERE category_name = :category_name");
        $categoryresult->execute([$itemCategory]);
        $category_id = $categoryresult->fetchColumn();
        if (!$category_id) throw new Exception("Invalid Category.");
           
         // Get sizeID
         $sizeresult = $pdo->prepare("SELECT size_id FROM sizes WHERE size_name = :size_name");
         $sizeresult->execute([$size]);
         $size_id = $sizeresult->fetchColumn();
         if (!$size_id) throw new Exception("Invalid Size.");

        // Get conditionID
        $conditionresult = $pdo->prepare("SELECT condition_id FROM conditions WHERE condition_name = :condition_name");
        $conditionresult->execute([$condition]);
        $condition_id = $conditionresult->fetchColumn();
        if (!$condition_id) throw new Exception("Invalid Condition.");
        
         // Get default status (AVAILABLE)
         $statusresult = $pdo->prepare("SELECT status_id FROM statuses WHERE status_name = 'Available'");
         $statusresult->execute();
         $status_id = $statusresult->fetchColumn();
         if (!$status_id) throw new Exception("Invalid Status.");

        // INSERT INTO ITEMS TABLE
        $itemresult= $pdo->prepare("INSERT INTO items(user_id, category_id, status_id, size_id, condition_id, title, description)
        VALUES(:user_id, :category_id, :status_id, :size_id, :condition_id, :title, :description)");
        $itemresult->execute([
            ':user_id' => $user_id, 
            ':category_id' => $category_id, 
            ':status_id' => $status_id, 
            ':size_id' => $size_id,
            ':condition_id' => $condition_id,
            ':title' => $itemName,
            ':description' => $description
        ]);

        
        // HANDLE FILE UPLOAD
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("No image uploaded.");
        }

        // Specify upload directory(Temporary storage of images) -- CODE FROM COPILOT
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
        }

        // Generate a unique file name to avoid conflicts
        $fileName = basename($_FILES['image']['name']);
        $filePath = $uploadDir . uniqid() . '_' . $fileName; // CODE FROM CO-PILOT

        // Move the uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            throw new Exception("Failed to save image.");
        }

        // INSERT image path INTO itemimages Table
        $imageresult = $pdo->prepare("INSERT INTO itemimages(item_id, image_path) 
        VALUES (:item_id, :image_path)");
        $imageresult->execute([':item_id' => $item_id, ':image_path' => $filePath]);

        // Success response
        $_SESSION['success'] = 'Item uploaded successfully!';
        header("Location: Upload_Items.html");
        exit();
                
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>




       

    
