<?php
require './parts/connect_db.php';

// 這是參照 add-api.php 修改

$output = [
  'postData' => $_POST,
  'success' => false,
  'errors' => [],
];

# 告訴用戶端, 資料格式為 JSON
header('Content-Type: application/json');
/*
if(empty($_POST['name']) or empty($_POST['email'])){
  $output['errors']['form'] = '缺少欄位資料';
  echo json_encode($output);
  exit;
}
*/


$course_id = $_POST['course_id'] ?? '';
$member_id = $_SESSION['role']['member_id'];


// TODO: 資料在寫入之前, 要檢查格式

// trim(): 去除頭尾的空白
// strlen(): 查看字串的長度
// mb_strlen(): 查看中文字串的長度

// $isPass = true;
// if (empty($name)) {
//   $isPass = false;
//   $output['errors']['name'] = '請填寫正確的姓名';
// }

// if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//   $isPass = false;
//   $output['errors']['email'] = 'email 格式錯誤';
// }

# 如果沒有通過檢查
// if (!$isPass) {
//   echo json_encode($output);
//   exit;
// }

# id字串 轉 數字型態,取得最大的 course_id ,再+1
// $id_sql = "SELECT max(substring(course_id, 2)+0) FROM course";
// $maxId = $pdo->query($id_sql)->fetch(PDO::FETCH_NUM)[0];
// $course_id = 'C'.str_pad(($maxId + 1),4,"0",STR_PAD_LEFT);


# 在欄位insert
$sql = "INSERT INTO `course_cart`(
  `course_id`,`member_id`
  ) VALUES (
  ?,?
  )";

// `course_cart_id`,
//  $course_id
//  {$course_id},?


$stmt = $pdo->prepare($sql); //固定

$stmt->execute([
  $course_id,
  $member_id,
]);

// 
$output['lastInsertId'] = $pdo->lastInsertId(); # 取得最新資料的 primary key
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);
