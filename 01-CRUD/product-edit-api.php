<?php
require './parts/connect_db.php';

header('Content-Type: application/json');

$output = [
    'postData' => $_POST,
    'success' => false,
    'errors' => [],
];

$product_id = isset($_POST['product_id']) ? strval($_POST['product_id']) : 0;

if (empty($product_id)) {
    $output['errors']['product_id'] = "沒有 PK";
    echo json_encode($output);
    exit;
}

$product_name = $_POST['product_name'] ?? '';
$category = $_POST['category'] ?? '';
$desc = $_POST['desc'] ?? '';
$spec = $_POST['spec'] ?? '';
$price = $_POST['price'] ?? '';
$discount = $_POST['discount'] ?? '';
$product_status_id = $_POST['product_status'] ?? '';

$sql2 = "UPDATE `stock` SET 
    `spec`=?,
    `price`=?
  WHERE `product_id`=?";

$stmt_stock = $pdo->prepare($sql2);
$stmt_stock->execute([
    $spec,
    $price,
    $product_id
]);

$sql = "UPDATE `product` SET 
  `product_name`=?,
  `category_id`=?,
  `desc`=?,
  `discount`=?,
  `product_status_id`=?
WHERE `product_id`=?";

$stmt_product = $pdo->prepare($sql);
$stmt_product->execute([
    $product_name,
    $category,
    $desc,
    $discount,
    $product_status_id,
    $product_id
]);

$output['rowCount'] = $stmt_product->rowCount();
$output['success'] = boolval($stmt_product->rowCount());
echo json_encode($output);
?>

