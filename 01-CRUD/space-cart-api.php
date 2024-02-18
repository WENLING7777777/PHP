<?php
require './parts/connect_db.php';

$output = [
  'postData' => $_POST,
  'success' => false,
  'error' => [],
];

header('Content-Type: application/json');

$member_id = $_SESSION['role']['member_id'];

$sql = "INSERT INTO `space_cart`(`member_id`, `space_id`, `booking_date`, `start_time`, `end_time`, `time`)VALUES('$member_id', ?, ?, ?, ?, ?)";
// 用?會自動加上''，若使用'%s'會有安全性問題SQL注入問題，要避免

$space = $_POST['space_id'] ?? '';
$date = $_POST['date'] ?? '';
$start_time = $_POST['start_time'] ?? '';
$end_time = $_POST['end_time'] ?? '';
$time = $_POST['time'] ?? '';

$isPass = true;

if(! $isPass){
  echo json_encode($output);
  exit;
}


$stmt = $pdo->prepare($sql);

$stmt->execute([
  $space,
  $date,
  $start_time,
  $end_time,
  $time,
]);

$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);

?>