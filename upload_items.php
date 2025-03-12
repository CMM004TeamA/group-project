<?php
session_start();
require_once 'connect.php';

//check if the user is logged in
if(!isset($_SESSION['user_id'])){
    header('Location: loginform.html');
    exit();
}

$user_id = $_SESSION['user_id'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
try {
    //GET FORM DATA AFTER SUBMISSION
    $itemName = $_POST['ItemName'];
    $grandparent_category_id = $_POST['grandparent_category'];
    $parent_category_id = $_POST['parent_category'];
    $child_category_id = $_POST['child_category'];
    $size = $_POST['size']; // may be empty
    $condition = $_POST['condition'];
    $description = $_POST['description'];
    $user_id = $_SESSION['user_id'];
    $status_id = 1;

    // Validate form data
    if(!$itemName || !$grandparent_category_id || !$parent_category_id || !$child_category_id || !$condition || !$description){
        throw new Exception('All fields are required except size.');
    } 

    //Get size ID(if provided)
    $size_id = null;
    if(!empty($_POST['size'])){
        $size_id = $_POST['size'];
        echo "Size ID: " . $size_id . "<br>";
    }

    //Get condition ID
    $conditionresult = $conn->prepare('SELECT condition_id FROM conditions WHERE condition_name = :condition_name');
    $conditionresult->execute(['condition_name' => $condition]);
    $condition_id = $conditionresult->fetchColumn();
    if (!$condition_id)
    throw new Exception('Invalid Condition');

    // //Debugging Print all parameters
    echo "<pre>";
    print_r(value: [
        ':user_id'=> $user_id,
        ':category_id'=> $child_category_id,
        ':status_id' => $status_id,
        ':size_id' => $size_id,
        ':condition_id' => $condition_id,
        ':title'=> $itemName,
        ':description' => $description
    ]);
    echo "</pre>";

    //insert into items table
    $itemresult = $conn->prepare(query: 'INSERT INTO items (user_id, category_id, status_id, size_id, condition_id, title, description) 
    VALUES (:user_id, :category_id, :status_id, :size_id, :condition_id, :title, :description)');
    $itemresult->execute(params:[
        'user_id' => $user_id,
        'category_id' => $child_category_id,
        'status_id' => $status_id,
        'size_id' => $size_id,
        'condition_id' => $condition_id,
        'title' => $itemName,
        'description' => $description
    ]);

    //Get last inserted item ID
    $item_id = $conn->lastInsertId();

    //Handle image upload
    if(!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK){
        throw new Exception('No image uploaded.');
    }
    
    //Valdidate file type and size
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    $maxFileSize = 1024 * 1024 * 5; //5MB

    if(!in_array($_FILES['image']['type'], $allowedTypes)){
        throw new Exception('Invalid file type. Only jpg, jpeg and png images are allowed.');
    }

    if($_FILES['image']['size'] > $maxFileSize){
        throw new Exception('File is too large. Max file size is 5MB.');
    }

    //Specify upload directory
    $uploadDir = 'uploads/'; 
    if (!is_dir(filename: $uploadDir)){
        mkdir(directory: $uploadDir, permissions: 0777, recursive: true); //creates an upload directory if it doesn't exist.
    }

    if(!is_writable(filename: $uploadDir)){
        throw new Exception('Upload directory is not writable.');
    }

    //Generate unique filename 
    $fileName = basename(path: $_FILES['image']['name']);
    $filePath = $uploadDir . uniqid() . '_' . $fileName; //so it doesn't mix up with other files

    //Move file to upload directory 
    if(!move_uploaded_file(from: $_FILES['image']['tmp_name'], to: $filePath)){
        throw new Exception('Failed to save image.');
    }

    //Insert into itemimages table in the database
    $imageresult = $conn->prepare('INSERT INTO itemimages (item_id, image_path) VALUES (:item_id, :image_path)');
    $imageresult->execute(['item_id' => $item_id, 'image_path' => $filePath]);

    //success message
    $_SESSION['success'] = 'Item uploaded successfully.';
    echo "Redirecting...";
    header('Location: upload_form.php');
    exit();
} catch (PDOException $e){
   echo "Database Error:" . $e->getMessage();
} catch (Exception $e){
    echo "Error:" . $e->getMessage();
}

}
?>
