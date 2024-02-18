<?php
require './parts/connect_db.php';

$place_id = isset($_GET['place_id'])?strval($_GET['place_id']):0;
if(! empty($place_id)){
  $sql = "UPDATE `place` SET `place_status_id` = '0' WHERE `place_id` = '{$place_id}'";
  $pdo->query($sql);
};

$come_from = 'place-list.php';
if(! empty($_SERVER['HTTP_REFERER'])){
  $come_from = $_SERVER['HTTP_REFERER'];
}

header("Location: $come_from");
?>