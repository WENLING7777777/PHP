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
$id_sql = "SELECT max(substring(space_id, 2)+0) FROM space";

$maxId = $pdo->query($id_sql)->fetch(PDO::FETCH_NUM)[0];
$space_id = 'S'.str_pad(($maxId + 1),4,"0",STR_PAD_LEFT);

$sql = "INSERT INTO `space`(`space_id`, `place_id`, `space`, `category_id`, `accommodate`, `time_rate`, `day_rate`)VALUES('$space_id', ?, ?, ?, ?, ?, ?)";
// 用?會自動加上''，若使用'%s'會有安全性問題SQL注入問題，要避免

$photo_sql = "INSERT INTO `space_photo`(`space_id`, `space_photo`)VALUES('$space_id', ?)";

$place = $_POST['place'] ?? '';
$space = $_POST['name'] ?? '';
$category = $_POST['category'] ?? '';
$accommodate = $_POST['accommodate'] ?? '';
$time_rate = $_POST['time_rate'] ?? '';
$day_rate = $_POST['day_rate'] ?? '';


$isPass = true;

if(! $isPass){
  echo json_encode($output);
  exit;
}


$stmt = $pdo->prepare($sql);

$stmt->execute([
  $place,
  $space,
  $category,
  $accommodate,
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

// echo json_encode([
//   'postData' => $_POST,
//   'rowCount' => $stmt->rowCount(),
// ])
// $output['lastInsertId'] = $pdo->lastInsertId();
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);

?>