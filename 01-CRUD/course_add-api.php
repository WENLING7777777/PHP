<?php
require './parts/connect_db.php';

/* *****************
# 會有 SQL injection
# 值如果包含單引號就會出錯
$sql = sprintf("INSERT INTO `address_book`(
  `name`, `email`, `mobile`, `birthday`, `address`, `created_at`
  ) VALUES (
    '%s', '%s', '%s', '%s', '%s', NOW()
  )", 
    $_POST['name'], 
    $_POST['email'],
    $_POST['mobile'],
    $_POST['birthday'],
    $_POST['address']
);
$stmt = $pdo->query($sql);
*/

$output = [
  'postData' => $_POST,
  'success' => false,
  // 'error' => '',
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

# 如果沒有通過檢查
// if (!$isPass) {
//   echo json_encode($output);
//   exit;
// }

# id字串 轉 數字型態,取得最大的 course_id ,再+1
$id_sql = "SELECT max(substring(course_id, 2)+0) FROM course";
$maxId = $pdo->query($id_sql)->fetch(PDO::FETCH_NUM)[0];
$course_id = 'C'.str_pad(($maxId + 1),4,"0",STR_PAD_LEFT);


# 在欄位insert
$sql = "INSERT INTO `course`(
  `course_id`,`category_id`,`course_name`,`course_time`,`place_id`,`people`,`course_status_id`,`course_price`,`course_plan`,`course_intro`,`teacher_id`,`notice`
  ) VALUES (
  '$course_id',?,?,?,?,?,?,?,?,?,?,?
  )";

// insert photo
$photo_sql = "INSERT INTO `course_photo`(`course_id`, `course_photo`)VALUES('$course_id', ?)";

// 
//  $course_id
//  {$course_id},?

//  , `email`, `mobile`, `birthday`, `address`, `created_at`
//  , ?, ?, ?, ?, NOW()

$stmt = $pdo->prepare($sql); //固定

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
//$course_id,
//
//$course_id,
//$email,
// $mobile,
// $birthday,
// $address,

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

// 
$output['lastInsertId'] = $pdo->lastInsertId(); # 取得最新資料的 primary key
$output['success'] = boolval($stmt->rowCount());
echo json_encode($output);
