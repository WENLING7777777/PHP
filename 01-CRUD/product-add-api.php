<?php
require './parts/connect_db.php';
$output = [
  'postData' => $_POST,
  'success' => false,
  'errors' => [],
];

header('Content-Type: application/json');

$id_sql = "SELECT max(substring(product_id, 2)+0) FROM product";

$maxId = $pdo->query($id_sql)->fetch(PDO::FETCH_NUM)[0];
$product_id = 'P'.str_pad(($maxId + 1),4,"0",STR_PAD_LEFT);

$product_name = $_POST['product_name'] ?? '';
$category = $_POST['category'] ?? '';
$desc = $_POST['desc'] ?? '';
$discount = $_POST['discount'] ?? '';
$product_status_id = $_POST['product_status'] ?? '';
$spec = $_POST['spec'] ?? ''; 
$price = $_POST['price'] ?? '';

$isPass = true;
// if (empty($product_id)) {
//   $isPass = false;
//   $output['errors']['product_id'] = '請填寫正確的ID';
// }

if (!$isPass) {
  echo json_encode($output);
  exit;
}

$sql = "INSERT INTO `product`(
  `product_id`, `product_name`, `category_id`, `desc`, `discount`, `product_status_id`
  ) VALUES (
    '$product_id', ?, ?, ?, ?, ?
  )";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $product_name,
    $category,
    $desc,
    $discount,
    $product_status_id
]);

$stock_sql = "INSERT INTO `stock` (`product_id`, `spec`, `price`) VALUES ('$product_id', ?, ?)";
$stock_stmt = $pdo->prepare($stock_sql);
$stock_stmt->execute([
    $spec,
    $price,
]);
$photo_sql = "INSERT INTO `product_photo`(`product_id`, `product_photo`)VALUES('$product_id', ?)";

for ($i = 1; $i < 11; $i++) {
  $photo = $_POST["photo".$i] ?? '';
  if($photo != ''){
    $stmt = $pdo->prepare($photo_sql);

    $stmt->execute([
      $photo,
    ]);
  }else{
    break;
  }
}

$output['lastInsertId'] = $pdo->lastInsertId();
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);
