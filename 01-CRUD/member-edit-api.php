<?php
require './parts/connect_db.php';

header('Content-Type: application/json');

$output = [
  'postData' => $_POST,
  'success' => false,
  'error' => [],
];

$member_id = isset($_POST['member_id']) ? strval($_POST['member_id']) : 0;

if(empty($member_id)){
  $output['error']['member_id'] = "沒有id";
  echo json_encode($output);
  exit;
}

$name = $_POST['name'] ?? '';
$nickname = $_POST['nickname'] ?? '';
$birthday = $_POST['birthday'] ?? '';
$phone = $_POST['phone'] ?? '';
$area = $_POST['areaSelect'] ?? '';
$address = $_POST['address'] ?? '';
$email = $_POST['email'] ?? '';
$role = $_POST['role'] ?? '';

$isPass = true;

if(! $isPass){
  echo json_encode($output);
  exit;
}

$sql = "UPDATE `member` SET 
  `name`=?,
  `nickname` =?,
  `birthday` =?,
  `phone`=?,
  `area_id`=?,
  `address`=?,
  `email` =?,
  `role_id` =?
WHERE `member_id`=? ";

$stmt = $pdo->prepare($sql);

$stmt->execute([
  $name,
  $nickname,
  $birthday,
  $phone,
  $area,
  $address,
  $email,
  $role,
  $member_id
]);

$output['rowCount'] = $stmt->rowCount();
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);
?>