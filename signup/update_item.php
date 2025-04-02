<?php
//require 'session.php';
require_once "../connect.php";

$item_id = $_GET["item_id"];

$sql = "SELECT * FROM items WHERE item_id = :item_id";

$stmt = $conn ->prepare($sql);
$stmt->execute(['item_id' => $item_id]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

//check if any data was obtained
if (($result) > 0) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ItemName = $_POST['ItemName'];
        $description = $_POST['description'];

        $update_item = "UPDATE items SET title = :title, description = :description WHERE item_id = :item_id";
        $update = $conn ->prepare($update_item);
        $update->execute([
            'title' => $ItemName,
    'description' => $description,
    'item_id' => $item_id
        ]);

        echo "<script>alert('Item Information updated successfully!'); window.location.href='admin_view_items.php'; </script>";
}
}
else {
    $error = $update->errorInfo();
    echo "Error: " . $error[2];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Items</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <style>
        .navbar-brand {
            color: purple;
        }
        .btn-purple {
            background-color: purple;
            color: white;
        }

        .form-select {
            width: 100%;
            border-color: purple;
        }
        .custom-border {
            border-color: purple !important; /* Purple border */
}

        main h2{
            text-align: center;
            color : purple;
            font-weight: bold;
    }
    </style>
</head>

<body>
<header class="mb-5 sticky-top">
        <nav class="navbar bg-light shadow-sm ">
            <div class="container">
                <a href="homepage.php" class="navbar-brand fw-bold text-purple fs-3">Team A</a>      
            </div>
        </nav>
    </header>   
    <main class="container mt-4">
        <h2>Update Items</h2>
        <p class="text-muted">Update Items Details.</p>
    </main>
    <div class="container bg-light p-4 shadow rounded">
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="ItemName" class="form-label">Title:</label>
                <input type="text" class="form-control custom-border" name="ItemName" value ="<?php echo $result['title'];?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control custom-border" name="description" rows="5"  required><?php echo $result['description'];?></textarea>
            </div>

            <button type="submit" class="btn btn-purple w-100" onclick="submitForm()">Submit Form</button>
            
        </form>
    </div>