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
$id_sql = "SELECT max(substring(place_id, 2)+0) FROM place";

$maxId = $pdo->query($id_sql)->fetch(PDO::FETCH_NUM)[0];
$place_id = 'P'.str_pad(($maxId + 1),4,"0",STR_PAD_LEFT);

$sql = "INSERT INTO `place`(`place_id`, `place`, `area_id`, `place_address`)VALUES('$place_id', ?, ?, ?)";
// 用?會自動加上''，若使用'%s'會有安全性問題SQL注入問題，要避免

$open_sql = "INSERT INTO `opening`(`place_id`, `weekday`, `start_time`, `end_time`)VALUES('$place_id', ?, ?, ?)";

$photo_sql = "INSERT INTO `place_photo`(`place_id`, `place_photo`)VALUES('$place_id', ?)";

$name = $_POST['name'] ?? '';
$area = $_POST['areaSelect'] ?? '';
$address = $_POST['address'] ?? '';
$start = $_POST['start_time'] ?? '';
$end = $_POST['end_time'] ?? '';

$isPass = true;

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
  $area,
  $address,
]);

for ($i = 1; $i < 8; $i++) {
  $week = $_POST["week".$i] ?? '';
  if($week != ''){

  $stmt = $pdo->prepare($open_sql);

  $stmt->execute([
    $week,
    $start,
    $end,
  ]);
  }
}

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

// echo json_encode([
//   'postData' => $_POST,
//   'rowCount' => $stmt->rowCount(),
// ])
// $output['lastInsertId'] = $pdo->lastInsertId();
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);

?>