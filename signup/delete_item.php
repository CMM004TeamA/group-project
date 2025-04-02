<?php
require_once "../connect.php";

$item_Id = $_GET["item_id"];

//deleting itemimages
$deleteItemImages = $conn->prepare("DELETE FROM itemimages WHERE item_id = :item_id");
$deleteItemImages->bindParam(':item_id', $item_Id, PDO::PARAM_INT);
$deleteItemImages->execute();

// deleting items table
$deleteItem = $conn->prepare("DELETE FROM items WHERE item_id = :item_id");
$deleteItem->bindParam(':item_id', $item_Id, PDO::PARAM_INT);
$deleteItem->execute();


if ($deleteItem->execute()) {
echo "<script type='text/javascript'>alert('Item Deleted!');</script>";
echo "<script>window.location.href = 'admin_view_items.php';</script>";
exit();
}
?>