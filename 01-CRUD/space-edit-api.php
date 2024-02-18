<?php
require './parts/connect_db.php';

header('Content-Type: application/json');

$output = [
  'postData' => $_POST,
  'success' => false,
  'error' => [],
];

$space_id = isset($_POST['space_id']) ? strval($_POST['space_id']) : 0;

if(empty($space_id)){
  $output['error']['space_id'] = "沒有id";
  echo json_encode($output);
  exit;
}

$name = $_POST['name'] ?? '';
$place = $_POST['place'] ?? '';
$category = $_POST['category'] ?? '';
$accommodate = $_POST['accommodate'] ?? '';
$time_rate = $_POST['time_rate'] ?? '';
$day_rate = $_POST['day_rate'] ?? '';
$status = $_POST['status'] ?? '';

$isPass = true;

if(! $isPass){
  echo json_encode($output);
  exit;
}

$sql = "UPDATE `space` SET 
  `space`=?,
  `place_id` =?,
  `category_id` =?,
  `accommodate`=?,
  `time_rate`=?,
  `day_rate`=?,
  `space_status_id` =?
WHERE `space_id`=? ";

$stmt = $pdo->prepare($sql);

$stmt->execute([
  $name,
  $place,
  $category,
  $accommodate,
  $time_rate,
  $day_rate,
  $status,
  $space_id
]);

// $output['rowCount'] = $stmt->rowCount();
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);
?>