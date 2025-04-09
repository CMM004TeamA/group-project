<?php
require_once "connect.php";

$item_id = trim($_GET['item_id']);

$sql2 = "DELETE FROM cartitems WHERE item_id = :item_id";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute(["item_id" => $item_id]);

$sql3 = "DELETE FROM reservations WHERE item_id = :item_id";
$stmt3 = $conn->prepare($sql3);
$stmt3->execute(["item_id" => $item_id]);

$sql1 = "DELETE FROM itemimages WHERE item_id = :item_id";
$stmt1 = $conn->prepare($sql1);
$stmt1->execute(["item_id" => $item_id]);

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
    echo json_encode(["error" => "Item deletion failed."]);
}
?>