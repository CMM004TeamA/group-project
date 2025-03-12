<?php
session_start();
include 'connect.php';

//check if the user is logged in
if(!isset($_SESSION['user_id'])){
    header('Location: loginform.html');
    exit;
}

try{
    //fetch data from the database
    function fetchData ($conn, $table): mixed
    {
        $stmt = $conn->prepare("SELECT * FROM $table");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $categories = fetchData($conn, table: 'categories');
    $conditions = fetchData($conn, table: 'conditions');
    $sizes = fetchData($conn, table: 'sizes');
    $sizecategories = fetchData($conn, table: 'sizecategories');

}
 catch(PDOException $e){
    die('Database error: ' . $e->getMessage());
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Items</title>
    <link rel="stylesheet" href="CSS/bootstrap.css">

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

<header class="mb-5">
        <nav class="navbar bg-light shadow-sm ">
            <div class="container">
                <a href="homepage.html" class="navbar-brand fw-bold text-purple fs-3">Team A</a>
            </div>
        </nav>
</header>
      
    <main class="container mt-4">
    <h2>Upload Items</h2>
        <b class="text-muted">Please use the form below to upload any items you would like to display on this website.
            Please ensure you write a sufficient amount of detail to ensure those reserving the item, know all the important details.</b>
    </main>
    <div class="container bg-light p-4 shadow rounded">
        <form action="upload_items.php" method="post" enctype ="multipart/form-data">
            <div class="mb-3">
                <label for="ItemName" class="form-label">Title:</label>
                <input type="text" class="form-control custom-border"  name="ItemName" required>
            </div>

            <!-- This code is from a youtube tutorial  -->
             <?php function renderDropdown($id, $label, $default, $data=[], $valueField = 'category_id', $labelField = 'category_name', $required = true) { ?> 
                <div class="mb-3">
                <label class="form-label"><?=htmlspecialchars($label)?></label>
                <select class="form-select" id="<?=htmlspecialchars(string: $id)?>" name="<?=htmlspecialchars($id)?>" <?= $required ? 'required' : '' ?>>
                    <option value=""><?=htmlspecialchars($default)?></option>
                    <?php foreach($data as $item): ?>
                        <option value="<?=htmlspecialchars(string: $item[$valueField])?>">
                            <?=htmlspecialchars($item[$labelField])?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php } ?>

            <?php 
            $grandparentCategories = array_filter($categories, function($cat): bool{
                return $cat['parent_category_id'] === null;
            }); 
            renderDropdown(id: 'grandparent_category', label: 'Grandparent Category', default: 'Select Grandparent Category', data: $grandparentCategories); //this code is from a youtube tutorial
            renderDropdown(id: 'parent_category', label: 'Parent Category', default: 'Select Parent Category', data: []);
            renderDropdown(id: 'child_category', label: 'Child Category', default: 'Select Child Category', data: []);
            renderDropdown(id: 'size', label: 'Size', default: 'Select Size (Optional)', data: $sizes, valueField: 'size_id', labelField: 'size_name', required: false);
            ?>

            <div class= 'mb-3'>
                <label for="condition" class="form-label">Item Condition:</label>
                <select class="form-select"  name="condition" required>
                    <option value="">Select Condition</option>
                    <?php foreach($conditions as $condition): ?>
                        <option value="<?=htmlspecialchars(string: $condition['condition_name'])?>">
                            <?=htmlspecialchars(string: $condition['condition_name'])?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control custom-border" name="description" rows="5" required></textarea>
            </div>  

            <div class="mb-3">
                <label for="image" class="form-label">Choose an image to upload:</label>
                <input type="file" class="form-control" name="image" required>  
            </div>
            <button type="submit" class="btn btn-purple w-100">Submit Form</button>
        </form>
    </div>

    <script>
        const categories = <?=json_encode($categories);?>;
        const sizes = <?=json_encode($sizes);?>;
        const sizecategories = <?=json_encode($sizecategories);?>;
    </script>
    <script src="sizecategories.js"></script>

                 
</body>
</html>
