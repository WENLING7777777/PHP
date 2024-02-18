<?php
require './parts/connect_db.php';

$output = [
  'postData' => $_POST,
  'success' => false,
  'error' => [],
];

header('Content-Type: application/json');

// if(empty($_POST['name']) or empty($_POST['email'])){
//   $output['error']['form'] = '缺少資料';
//   echo json_encode($output);
//   exit;
// }
$id_sql = "SELECT max(substring(member_id, 2)+0) FROM member";

$maxId = $pdo->query($id_sql)->fetch(PDO::FETCH_NUM)[0];
$member_id = 'A'.str_pad(($maxId + 1),4,"0",STR_PAD_LEFT);

$sql = "INSERT INTO `member`(`member_id`, `name`, `nickname`, `password`, `birthday`, `gender`, `phone`, `area_id`, `address`, `email`, `create_date`)VALUES('$member_id', ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
// 用?會自動加上''，若使用'%s'會有安全性問題SQL注入問題，要避免


$name = $_POST['name'] ?? '';
$nickname = $_POST['nickname'] ?? '';
$password = $_POST['password'] ?? '';
$birthday = $_POST['birthday'] ?? '';
$gender = $_POST['gender'] ?? '';
$phone = $_POST['phone'] ?? '';
$area = $_POST['areaSelect'] ?? '';
$address = $_POST['address'] ?? '';
$email = $_POST['email'] ?? '';

$isPass = true;
if(empty($name)){
  $isPass = false;
  $output['error']['name'] = '請輸入正確姓名';
}

// if(! filter_var($email, FILTER_VALIDATE_EMAIL)){
//   $isPass = false;
//   $output['error']['email'] = 'email 格式錯誤';
// }

if(! $isPass){
  echo json_encode($output);
  exit;
}


$stmt = $pdo->prepare($sql);

$stmt->execute([
  $name,
  $nickname,
  $password,
  $birthday,
  $gender,
  $phone,
  $area,
  $address,
  $email,
]);


// echo json_encode([
//   'postData' => $_POST,
//   'rowCount' => $stmt->rowCount(),
// ])
// $output['lastInsertId'] = $pdo->lastInsertId();
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);

?>