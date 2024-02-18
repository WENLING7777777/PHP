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
$id_sql = "SELECT max(substring(device_id, 2)+0) FROM device";

$maxId = $pdo->query($id_sql)->fetch(PDO::FETCH_NUM)[0];
$device_id = 'D'.str_pad(($maxId + 1),4,"0",STR_PAD_LEFT);

$sql = "INSERT INTO `device`(`device_id`, `device_name`, `category_id`, `brand_id`, `model`, `device_intro`, `place_id`, `basic_fee`, `time_rate`, `day_rate`)VALUES('$device_id', ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$photo_sql = "INSERT INTO `device_photo`(`device_id`, `device_photo`)VALUES('$device_id', ?)";

$device = $_POST['name'] ?? '';
$category = $_POST['category'] ?? '';
$brand = $_POST['brand'] ?? '';
$model = $_POST['model'] ?? '';
$intro = $_POST['intro'] ?? '';
$place = $_POST['place'] ?? '';
$basic = $_POST['basic'] ?? '';
$time_rate = $_POST['time_rate'] ?? '';
$day_rate = $_POST['day_rate'] ?? '';

$isPass = true;

if(! $isPass){
  echo json_encode($output);
  exit;
}

$stmt = $pdo->prepare($sql);

$stmt->execute([
  $device,
  $category,
  $brand,
  $model,
  $intro,
  $place,
  $basic,
  $time_rate,
  $day_rate,
]);

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

$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);

?>