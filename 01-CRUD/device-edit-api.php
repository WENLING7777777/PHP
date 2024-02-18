<?php
require './parts/connect_db.php';

header('Content-Type: application/json');

$output = [
  'postData' => $_POST,
  'success' => false,
  'error' => [],
];

$device_id = isset($_POST['device_id']) ? strval($_POST['device_id']) : 0;

if(empty($device_id)){
  $output['error']['device_id'] = "沒有id";
  echo json_encode($output);
  exit;
}

$name = $_POST['name'] ?? '';
$category = $_POST['category'] ?? '';
$brand = $_POST['brand'] ?? '';
$model = $_POST['model'] ?? '';
$intro = $_POST['intro'] ?? '';
$place = $_POST['place'] ?? '';
$basic = $_POST['basic'] ?? '';
$time_rate = $_POST['time_rate'] ?? '';
$day_rate = $_POST['day_rate'] ?? '';
$status = $_POST['status'] ?? '';

$isPass = true;

if(! $isPass){
  echo json_encode($output);
  exit;
}

$sql = "UPDATE `device` SET 
  `device_name`=?,
  `category_id` =?,
  `brand_id` =?,
  `model`=?,
  `device_intro`=?,
  `place_id` =?,
  `basic_fee`=?,
  `time_rate`=?,
  `day_rate`=?,
  `device_status_id` =?
WHERE `device_id`=? ";

$stmt = $pdo->prepare($sql);

$stmt->execute([
  $name,
  $category,
  $brand,
  $model,
  $intro,
  $place,
  $basic,
  $time_rate,
  $day_rate,
  $status,
  $device_id
]);

// $output['rowCount'] = $stmt->rowCount();
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);
?>