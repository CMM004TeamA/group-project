<?php
require 'session.php';
require_once "connect.php";

$sql = "
SELECT
    i.item_id,
    i.title,
    i.description,
    ii.image_path  
FROM itemimages ii
INNER JOIN items i ON ii.item_id = i.item_id 
";

$stmt = $conn->prepare($sql);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamA Bags</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include "header.php"; ?>

    <main class="container">
        <h2 class="text-center text-purple mb-4">ALL ITEMS</h2>
        <div class="row g-4" id="itemContainer">
            <?php
            if ($result) {
                foreach ($result as $row) {
                    ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card text-center h-75">
                            <!--image tag with closed tag -->
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" class="card-img h-75"
                                alt="Item Image"><br>
                            <p><strong>Title:</strong> <?php echo htmlspecialchars($row['title']); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                            <p>
                                <a href="update_item.php?item_id=<?php echo $row['item_id']; ?>">Update</a> /
                                <a href="#" onclick="confirmDelete(<?php echo $row['item_id']; ?>)">Delete</a>
                            </p>
                        </div> <!-- Closed card div -->
                    </div> <!-- Closed col-md-4 -->
                    <?php
                }
            } else {
                echo "There are no items available.";
            }



            //JAVASCRIPT FOR POP UP MESSAGE
            ?>

            <script type="text/javascript">

                function confirmDelete(item_Id) {
                    var confirmation = confirm("Are you sure you want to delete this item?");

                    if (confirmation) {
                        window.location.href = "delete_items.php?item_id=" + item_Id;
                    } else {
                        return false;
                    }
                }
            </script>
        </div>
    </main>

    <script src="assets/js/search.js"></script>
</body>
<!-- Footer -->
<footer class="bg-secondary text-center fw-bold py-3">
    <p>&copy; 2025 CMM004 Team A. All rights reserved || Support Contact Email:
        <a href="mailto:teamacmm004@gmail.com" class="text-decoration-none text-purple">teamacmm004@gmail.com</a>
    </p>
</footer>

</html>