<?php
require './parts/connect_db.php';

$member_id = isset($_GET['member_id'])?strval($_GET['member_id']):0;
if(! empty($member_id)){
  $sql = "DELETE FROM member WHERE member_id = '{$member_id}'";
  $pdo->query($sql);
};

$come_from = 'member-list.php';
if(! empty($_SERVER['HTTP_REFERER'])){
  $come_from = $_SERVER['HTTP_REFERER'];
}

header("Location: $come_from");
?>