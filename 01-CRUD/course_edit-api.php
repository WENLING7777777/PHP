<?php
require './parts/connect_db.php';

# 告訴用戶端, 資料格式為 JSON
header('Content-Type: application/json');
#echo json_encode($_POST);
#exit; // 結束程式


$output = [
  'postData' => $_POST,
  'success' => false,
  // 'error' => '',
  'errors' => [],
];


// 取得資料的 PK
$course_id = isset($_POST['course_id']) ? strval($_POST['course_id']) : 0;

if (empty($course_id)) {
  $output['errors']['course_id'] = "沒有 PK";
  echo json_encode($output);
  exit; // 結束程式
}

$category_id = $_POST['category_id'] ?? '';
$course_name = $_POST['course_name'] ?? '';
$course_time = $_POST['course_time'] ?? '';
$place_id = $_POST['place_id'] ?? '';
$people = $_POST['people'] ?? '';
$course_status_id = $_POST['course_status_id'] ?? '';
$course_price = $_POST['course_price'] ?? '';
$course_plan = $_POST['course_plan'] ?? '';
$course_intro = $_POST['course_intro'] ?? '';
$teacher_id = $_POST['teacher_id'] ?? '';
$notice = $_POST['notice'] ?? '';

// $email = $_POST['email'] ?? '';
// $mobile = $_POST['mobile'] ?? '';
// $birthday = $_POST['birthday'] ?? '';
// $address = $_POST['address'] ?? '';



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

// # 如果沒有通過檢查
// if (!$isPass) {
//   echo json_encode($output);
//   exit;
// }

# id字串 轉 數字型態,取得最大的 course_id ,再+1
// $id_sql = "SELECT max(substring(course_id, 2)+0) FROM course";
// $maxId = $pdo->query($id_sql)->fetch(PDO::FETCH_NUM)[0];
// $course_id = 'C'.str_pad(($maxId + 1),4,"0",STR_PAD_LEFT);

$sql = "UPDATE `course` SET 
  `category_id`=?,
  `course_name`=?,
  `course_time`=?,
  `place_id`=?,
  `people`=?,
  `course_status_id`=?,
  `course_price`=?,
  `course_plan`=?,
  `course_intro`=?,
  `teacher_id`=?,
  `notice`=?
WHERE `course_id`='$course_id' ";
//最後不逗號
//  `email`=?,
//  `mobile`=?,
//  `birthday`=?,
//  `address`=?
//  {$course_id}

$stmt = $pdo->prepare($sql);

$stmt->execute([
  $category_id,
  $course_name,
  $course_time,
  $place_id,
  $people,
  $course_status_id,
  $course_price,
  $course_plan,
  $course_intro,
  $teacher_id,
  $notice,
]);
// 最後要逗號
// $email,
// $mobile,
// $birthday,
// $address,
// $course_id


$output['rowCount'] = $stmt->rowCount();
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);
