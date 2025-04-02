<?php
require 'session.php';
require_once "../connect.php";

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
    <link rel="stylesheet" href="http://localhost/WEBSITES/DONATION%20WEBSITE/group-project-main/group-project-main/Assets/css/bootstrap.css">
    <link rel="stylesheet" href="http://localhost/WEBSITES/DONATION%20WEBSITE/group-project-main/group-project-main/Assets/css/style.css">
</head>

<body>
    <header class="mb-5">
        <nav class="navbar bg-light shadow-sm ">
            <div class="container">
                <a href="homepage.html" class="navbar-brand fw-bold text-purple fs-3">Team A</a>
                
                <div>
                    <a href = "profile.php" class="btn btn-outline-purple">Profile</a>
                    <a href = "signout.php" class="btn btn-purple">Sign Out</a>
                </div>
            </div>
        </nav>
    </header>

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
                            <img src="http://localhost/WEBSITES/DONATION%20WEBSITE/group-project-main/group-project-main/<?php echo htmlspecialchars($row['image_path']); ?>" class="card-img h-75" alt="Item Image"><br>
                            <p><strong>Title:</strong> <?php echo htmlspecialchars($row['title']); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                            <p>
                                <a href="http://localhost/WEBSITES/DONATION%20WEBSITE/group-project-main/group-project-main/signup/update_item.php?item_id=<?php echo $row['item_id']; ?>">Update</a> /
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
            window.location.href = "http://localhost/WEBSITES/DONATION%20WEBSITE/group-project-main/group-project-main/signup/delete_item.php?item_id=" + item_Id;
        } else {
            return false;
        }
    }
</script>
        </div>
    </main>

    <script src="assets/js/search.js"></script>
</body>

</html>
