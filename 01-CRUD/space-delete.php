<?php
require './parts/connect_db.php';

$space_id = isset($_GET['space_id'])?strval($_GET['space_id']):0;
if(! empty($space_id)){
  $sql = "UPDATE `space` SET `space_status_id` = '0' WHERE `space_id` = '{$space_id}'";
  $pdo->query($sql);
};

$come_from = 'space-list.php';
if(! empty($_SERVER['HTTP_REFERER'])){
  $come_from = $_SERVER['HTTP_REFERER'];
}

header("Location: $come_from");
?>