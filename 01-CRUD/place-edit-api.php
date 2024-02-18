<?php
require './parts/connect_db.php';

header('Content-Type: application/json');

$output = [
  'postData' => $_POST,
  'success' => false,
  'error' => [],
];

$place_id = isset($_POST['place_id']) ? strval($_POST['place_id']) : 0;

if(empty($place_id)){
  $output['error']['place_id'] = "沒有id";
  echo json_encode($output);
  exit;
}

$name = $_POST['name'] ?? '';
$area = $_POST['areaSelect'] ?? '';
$address = $_POST['address'] ?? '';

$isPass = true;

if(! $isPass){
  echo json_encode($output);
  exit;
}

$sql = "UPDATE `place` SET 
  `place`=?,
  `area_id`=?,
  `place_address`=?
WHERE `place_id`=? ";

$stmt = $pdo->prepare($sql);

$stmt->execute([
  $name,
  $area,
  $address,
  $place_id
]);

$output['rowCount'] = $stmt->rowCount();
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);
?>