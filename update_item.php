<?php
//Assisted with AI
require 'session.php';
require_once "connect.php";

function fetchData($conn, $table) {
    $stmt = $conn->prepare("SELECT * FROM $table");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch categories from the database
$categories = fetchData($conn, "categories");
$conditions = fetchData($conn, "conditions");
$sizes = fetchData($conn, "sizes");
$sizecategories = fetchData($conn, "sizecategories");

// Filter categories into grandparent, parent, and child
$grandparentCategories = array_filter($categories, function($cat) {
    return $cat['parent_category_id'] === null; 
});
$grandparentIds = array_column($grandparentCategories, 'category_id'); 

$parentCategories = array_filter($categories, function($cat) use ($grandparentIds) {
    return in_array($cat['parent_category_id'], $grandparentIds); 
});
$parentIds = array_column($parentCategories, 'category_id'); 


$childCategories = array_filter($categories, function($cat) use ($parentIds) {
    return in_array($cat['parent_category_id'], $parentIds); 
});

$item_id = $_GET['item_id'] ?? null;
if (!$item_id) die("No item specified.");

$stmt = $conn->prepare("
    SELECT i.*, 
           c1.category_id AS grandparent_id,
           c2.category_id AS parent_id,
           c3.category_id AS child_id,
           s.size_id,
           cond.condition_name
    FROM items i
    LEFT JOIN categories c3 ON i.category_id = c3.category_id
    LEFT JOIN categories c2 ON c3.parent_category_id = c2.category_id
    LEFT JOIN categories c1 ON c2.parent_category_id = c1.category_id
    LEFT JOIN sizes s ON i.size_id = s.size_id
    LEFT JOIN conditions cond ON i.condition_id = cond.condition_id
    WHERE i.item_id = :item_id
");
$stmt->execute(['item_id' => $item_id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$item) die("Item not found.");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $itemName = $_POST["ItemName"];
    $grandparent_category_id = $_POST['grandparent_category']; 
    $parent_category_id = $_POST['parent_category']; 
    $child_category_id = $_POST['child_category']; 
    $size_id = $_POST['size'] ?: null;
    $condition_id = $_POST["condition"];
    $description = $_POST["description"];

    $update = $conn->prepare("
        UPDATE items SET 
            title = :title,
            description = :description,
            category_id = :category_id,
            size_id = :size_id,
            condition_id = :condition_id
        WHERE item_id = :item_id
    ");
    $update->execute([
        'title' => $itemName,
        'description' => $description,
        'category_id' => $child_category_id,
        'size_id' => $size_id,
        'condition_id' => $condition_id,
        'item_id' => $item_id
    ]);

    echo "<script>alert('Item Information updated successfully!'); window.location.href='admin_view_items.php'; </script>";
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
            border-color: purple; 
        }

        main h2 {
            text-align: center;
            color: purple;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header class="mb-5 sticky-top">
        <nav class="navbar bg-light shadow-sm">
            <div class="container">
                <a href="index.php" class="navbar-brand fw-bold text-purple fs-3">Team A</a>      
            </div>
        </nav>
    </header>   
    <main class="container mt-4">
        <h2>Update Items</h2>
        <p class="text-muted">Please use the form below to update any item you would like to display on this website with details.</p>
    </main>
    <div class="container bg-light p-4 shadow rounded">
        <form method="post" enctype="multipart/form-data" onsubmit="return confirmUpdate();">
            <div class="mb-3">
                <label for="ItemName" class="form-label">Title:</label>
                <input type="text" class="form-control custom-border" name="ItemName" value="<?= htmlspecialchars($item['title']) ?>" required>
            </div>

      <!-- Grandparent Dropdown -->
      <div class="mb-3">
      <label class="form-label">GrandParent Category:</label>
<select class='form-select' name='grandparent_category' id='grandparent_category' required>
    <option value=''>Select Grandparent Category</option>
    <?php foreach ($categories as $cat): ?>
        <?php if ($cat['parent_category_id'] === null): ?>
            <option value="<?= $cat['category_id'] ?>" <?= ($item['grandparent_id'] == $cat['category_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['category_name']) ?>
            </option>
        <?php endif; ?>
    <?php endforeach; ?>
</select>
        </div>

<!-- Parent Dropdown -->
<div class="mb-3">
<label class="form-label">Parent Category:</label>
<select class='form-select' name='parent_category' id='parent_category' data-selected="<?= $item['parent_id'] ?>" required>
    <option value=''>Select Parent Category</option>
</select>
        </div>

<!-- Child Dropdown -->
<div class="mb-3">
<label class="form-label">Child Category:</label>
<select class='form-select' name='child_category' id='child_category' data-selected="<?= $item['child_id'] ?>" required>
    <option value=''>Select Child Category</option>
</select>
        </div>

            <!-- Condition -->
            <div class="mb-3">
                <label for="condition" class="form-label">Item Condition:</label>
                <select class="form-select" name="condition" required>
                    <option value="">Select Condition</option>
                    <?php foreach ($conditions as $condition): ?>
                        <option value="<?= $condition['condition_id'] ?>" <?= ($item['condition_id'] == $condition['condition_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($condition['condition_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea id="description" class="form-control custom-border" name="description" rows="5" required><?= htmlspecialchars($item['description']) ?></textarea>
            </div>

            <!-- Image -->
            <div class="mb-3">
                <label for="image" class="form-label">Choose an image to upload (optional):</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-purple w-100">Update Item</button>
        </form>
    </div>

    <script>
        function confirmUpdate() {
            return confirm("Are you sure you want to update this item?");
        }
    </script>
</body>

<script>
    //Assisted with AI
const allCategories = <?= json_encode($categories) ?>;

// Filter and populate dropdowns dynamically
function populateDropdown(dropdown, options, selectedId = null) {
    dropdown.innerHTML = `<option value="">Select ${dropdown.id.replace('_', ' ')}</option>`;
    options.forEach(opt => {
        const selected = opt.category_id == selectedId ? 'selected' : '';
        dropdown.innerHTML += `<option value="${opt.category_id}" ${selected}>${opt.category_name}</option>`;
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const grandparentSelect = document.getElementById("grandparent_category");
    const parentSelect = document.getElementById("parent_category");
    const childSelect = document.getElementById("child_category");

    
    const selectedGrandparent = grandparentSelect.value;
    const selectedParent = parentSelect.getAttribute("data-selected");
    const selectedChild = childSelect.getAttribute("data-selected");

    
    if (selectedGrandparent) {
        const parents = allCategories.filter(cat => cat.parent_category_id == selectedGrandparent);
        populateDropdown(parentSelect, parents, selectedParent);
    }

    if (selectedParent) {
        const children = allCategories.filter(cat => cat.parent_category_id == selectedParent);
        populateDropdown(childSelect, children, selectedChild);
    }

    // Change parent and child if grandparent changes
    grandparentSelect.addEventListener("change", () => {
        const parentOptions = allCategories.filter(cat => cat.parent_category_id == grandparentSelect.value);
        populateDropdown(parentSelect, parentOptions);
        populateDropdown(childSelect, []); 
    });

    // 
    parentSelect.addEventListener("change", () => {
        const childOptions = allCategories.filter(cat => cat.parent_category_id == parentSelect.value);
        populateDropdown(childSelect, childOptions);
    });
});
</script>

</html>
