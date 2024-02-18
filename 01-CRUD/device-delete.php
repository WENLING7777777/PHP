<?php
require './parts/connect_db.php';

$device_id = isset($_GET['device_id'])?strval($_GET['device_id']):0;
if(! empty($device_id)){
  $sql = "UPDATE `device` SET `device_status_id` = '0' WHERE `device_id` = '{$device_id}'";
  $pdo->query($sql);
};

$come_from = 'device-list.php';
if(! empty($_SERVER['HTTP_REFERER'])){
  $come_from = $_SERVER['HTTP_REFERER'];
}

header("Location: $come_from");
?>