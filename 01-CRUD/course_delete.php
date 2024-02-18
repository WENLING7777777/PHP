<?php
require './parts/connect_db.php';

$course_id = isset($_GET['course_id']) ? strval($_GET['course_id']) : 0;

if(! empty($course_id)){
  // 先刪course_photo
  $photo_sql = "DELETE FROM course_photo WHERE course_id='{$course_id}'";
  $pdo->query($photo_sql);

  // 再刪course_photo
  $sql = "DELETE FROM course WHERE course_id='{$course_id}'";
  $pdo->query($sql);
}

$come_from = 'course_list.php';
if(! empty($_SERVER['HTTP_REFERER'])){
  $come_from = $_SERVER['HTTP_REFERER'];
}

header("Location: $come_from");