<?php
require './parts/connect_db.php';

$output = [
    'postData' => $_POST,
    'success' => false,
    'errors' => [],
];

header('Content-Type: application/json');

// if(empty($_POST['$exhibition_name']) or empty($_POST['exhibition_desc'])){
//     $output['errors']['form'] = '缺少欄位資料';
//     echo json_encode($output);
//     exit;
// }
$id_sql = "SELECT max(substring(exhibition_id, 2)+0) FROM exhibition";

$maxId = $pdo->query($id_sql)->fetch(PDO::FETCH_NUM)[0];
$exhibition_id = 'E'.str_pad(($maxId + 1),4,"0",STR_PAD_LEFT);

$sql = "INSERT INTO `exhibition`(`exhibition_id`, `exhibition_type_id`, `exhibition_name`, `exhibition_people`, `start_time`, `end_time`, `space_id`, `exhibition_desc`)VALUES('$exhibition_id', ?, ?, ?, ?, ?, ?, ?)";

$photo_sql = "INSERT INTO `exhibition_photo`( `exhibition_id`, `exhibition_photo`)VALUES('$exhibition_id', ?)";



$exhibition_type_id = $_POST['exhibition_type_id'] ?? '';
$exhibition_name = $_POST['exhibition_name'] ?? '';
$exhibition_people = $_POST['exhibition_people'] ?? '';
$start_time = $_POST['start_time'] ?? '';
$end_time = $_POST['end_time'] ?? '';
$space_id = $_POST['space_id'] ?? '';
$exhibition_desc = $_POST['exhibition_desc'] ?? '';

$exhibition_type_id = intval($exhibition_type_id);

$isPass = true;

if(! $isPass){
    echo json_encode($output);
    exit;
  }

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $exhibition_type_id,
    $exhibition_name,
    $exhibition_people,
    $start_time,
    $end_time,
    $space_id,
    $exhibition_desc,
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



// function generateUniqueExhibitionID($pdo) {
//     $prefix = 'E';
//     $id = 0;
  
//     $query = "SELECT MAX(CAST(SUBSTRING(exhibition_id, 2) AS SIGNED)) AS max_id FROM exhibition";
//     $result = $pdo->query($query);
//     $row = $result->fetch(PDO::FETCH_ASSOC);

//     if ($row['max_id']) {
//         $id = intval($row['max_id']) + 1;
//     }

//     return $prefix . str_pad($id, 4, '0', STR_PAD_LEFT);
// }

// $exhibition_id = generateUniqueExhibitionID($pdo);




$output['success'] = boolval($stmt->rowCount());

echo json_encode($output);
?>
