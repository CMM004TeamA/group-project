<?php
require_once "connect.php";

$item_id = trim($_GET['item_id']);

$sql1 = "DELETE FROM itemimages WHERE item_id = :item_id";
$stmt1 = $conn->prepare($sql1);
$stmt1->execute(["item_id" => $item_id]);

if ($stmt1->rowCount()>0) {
$sql = "DELETE FROM items WHERE item_id = :item_id";
$stmt = $conn->prepare($sql);
$stmt->execute(["item_id" => $item_id]);

if ($stmt->rowCount()>0) {
    http_response_code(200);
    echo "<script type='text/javascript'>alert('Item deleted successfully.!');</script>";
echo "<script>window.location.href = 'admin_view_items.php';</script>";
}
 else {
        http_response_code(400);
        echo json_encode(["error" => "Only GET requests are supported"]); 
    }   
    }
else {
    http_response_code(400);
    echo json_encode(["error" => "Item deletion failed."]);
}
?>

    
