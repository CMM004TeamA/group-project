<?php
session_start();
require_once "connect.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.html");
    exit;
}

try {
    // Fetch data from the database
    function fetchData($conn, $table)
    {
        $stmt = $conn->prepare("SELECT * FROM $table");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $categories = fetchData($conn, "categories");
    $conditions = fetchData($conn, "conditions");
    $sizes = fetchData($conn, "sizes");
    $sizecategories = fetchData($conn, "sizecategories");

    // Check if there is a success message in the session
$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : null;

// Clear the session success message to prevent it from showing after page refresh
unset($_SESSION['success']);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Items</title>
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
                <a href="index.php" class="navbar-brand fw-bold text-purple fs-3">Team A</a>      
            </div>
        </nav>
    </header>   
    <main class="container mt-4">
        <h2>Upload Items</h2>
        <p class="text-muted">Please use the form below to upload any item you would like to display on this website with details.</p>
    </main>
    <div class="container bg-light p-4 shadow rounded">
        <form action="upload_items2.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="ItemName" class="form-label">Title:</label>
                <input type="text" class="form-control custom-border" name="ItemName" required>
            </div>
            <?php function renderDropdown($id, $label, $default, $data = [], $valueField = 'category_id', $labelField = 'category_name', $required = true)
            { ?>
                <div class="mb-3">
                    <label class="form-label"><?= htmlspecialchars($label) ?></label>
                    <select class="form-select" id="<?= htmlspecialchars($id) ?>" name="<?= htmlspecialchars($id) ?>"
                        <?= $required ? 'required' : '' ?>>
                        <option value=""><?= htmlspecialchars($default) ?></option>
                        <?php foreach ($data as $item): ?>
                            <option value="<?= htmlspecialchars($item[$valueField]) ?>">
                                <?= htmlspecialchars($item[$labelField]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php } ?>

            <?php
            $grandparentCategories = array_filter($categories, function ($cat) {
                return $cat['parent_category_id'] === null;
            });
            renderDropdown("grandparent_category", "Grandparent Category", "Select Grandparent Category", $grandparentCategories);
            renderDropdown("parent_category", "Parent Category", "Select Parent Category", []);
            renderDropdown("child_category", "Child Category", "Select Child Category", []);
            renderDropdown("size", "Size", "Select Size (Optional)", $sizes, 'size_id', 'size_name', false); // Make size optional
            ?>

            <div class="mb-3">
                <label for="condition" class="form-label">Item Condition:</label>
                <select class="form-select" name="condition" required>
                    <option value="">Select Condition</option>
                    <?php foreach ($conditions as $condition): ?>
                        <option value="<?= htmlspecialchars($condition['condition_name']) ?>">
                            <?= htmlspecialchars($condition['condition_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea id="description" class="form-control custom-border" name="description" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Choose an image to upload:</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-purple w-100" onclick="submitForm()">Submit Form</button>
            
        </form>
    </div>

    <?php
        // If there is a success message, display it using JavaScript
        if ($successMessage) {
            echo "<script>
                window.onload = function() {
                    alert('" . addslashes($successMessage) . "');
                };
            </script>";
        }
    ?>

<script>
    // This function will be called when the user clicks the submit button
    function submitForm() {

        // If form is valid, submit the form
        document.getElementById("uploadForm").submit();
        alert('Your item has been uploaded successfully!');
    }
</script>

    
    <script>
        const categories = <?= json_encode($categories); ?>;
        const sizes = <?= json_encode($sizes); ?>;
        const sizecategories = <?= json_encode($sizecategories); ?>;
    </script>
    <script src="sizecategories.js"></script>
</body>

</html>