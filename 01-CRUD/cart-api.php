<?php
require './parts/connect_db.php';

$member_id = $_SESSION['role']['member_id'];


if(! empty($member_id)){
  $sql = "DELETE FROM `space_cart` WHERE `member_id` = '{$member_id}'";
  $pdo->query($sql);
  $sql = "DELETE FROM `course_cart` WHERE `member_id` = '{$member_id}'";
  $pdo->query($sql);
};

$come_from = 'fin.php';
// if(! empty($_SERVER['HTTP_REFERER'])){
//   $come_from = $_SERVER['HTTP_REFERER'];
// }

header("Location: $come_from");
?>