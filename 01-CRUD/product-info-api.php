<?php
require './parts/connect_db.php';

$output = [
  'postData' => $_POST,
  'success' => false,
  'error' => [],
];

header('Content-Type: application/json');

if (empty($_POST['product_id']) || empty($_POST['member_id']) || empty($_POST['spec']) || empty($_POST['quantity'])) {
  $output['error']['form'] = '缺少必要的資料';
  echo json_encode($output);
  exit;
}

$product_id = $_POST['product_id'];
$member_id = $_POST['member_id'];
$spec = $_POST['spec'];
$quantity = $_POST['quantity'];

// Assuming product_cart_id is an auto-incremented primary key
$sql = "INSERT INTO `product_cart` (`product_id`, `member_id`, `spec`, `quantity`) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$product_id, $member_id, $spec, $quantity]);

$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);
?>
