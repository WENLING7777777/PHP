<?php
require './parts/connect_db.php';

header('Content-Type: application/json');



$output = [
  'postData' => $_POST,
  'success' => false,
  // 'error' => '',
  'errors' => [],
];



$exhibition_id = isset($_POST['exhibition_id']) ? strval($_POST['exhibition_id']) : 0;

if (empty($exhibition_id)) {
  $output['errors']['exhibition_id'] = "沒有 id";
  echo json_encode($output);
  exit; 
}


$exhibition_type_id = $_POST['exhibition_type_id'] ?? '';
$exhibition_name = $_POST['exhibition_name'] ?? '';
$exhibition_people = $_POST['exhibition_people'] ?? '';
$start_time = $_POST['start_time'] ?? '';
$end_time = $_POST['end_time'] ?? '';
$space_id = $_POST['space_id'] ?? '';
$exhibition_desc = $_POST['exhibition_desc'] ?? '';



$isPass = true;
// if (empty($exhibition_name)) {
//   $isPass = false;
//   $output['errors']['exhibition_name'] = '請輸入展覽名稱';
// }
# 如果沒有通過檢查
if (!$isPass) {
  echo json_encode($output);
  exit;
}

$sql = "UPDATE `exhibition` SET 
  `exhibition_type_id`=?,
  `exhibition_name`=?,
  `exhibition_people`=?,
  `start_time`=?,
  `end_time`=?,
  `space_id`=?,
  `exhibition_desc`=?
WHERE `exhibition_id`=? ";

$stmt = $pdo->prepare($sql);

$stmt->execute([
  $exhibition_type_id,
  $exhibition_name,
  $exhibition_people,
  $start_time,
  $end_time,
  $space_id,
  $exhibition_desc,
  $exhibition_id
]);

$output['rowCount'] = $stmt->rowCount();
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);
?>
