<?php

require './parts/connect_db.php';

$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用的
    'code' => 0,
];



if(! empty($_POST['account']) and ! empty($_POST['password'])){

    $sql = "SELECT * FROM `member` JOIN `role` ON member.role_id = role.role_id WHERE `email`=? ";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $_POST['account']
    ]);

    $row = $stmt->fetch();

    if(empty($row)){
        $output['code'] = 410; # 帳號是錯的
    } else {
        if($_POST['password']==$row['password']){
        // if(password_verify($_POST['password'], $row['password'])){
            # 密碼也是對的
            $_SESSION['role'] = $row;
            $output['success'] = true;
        } else {
            # 密碼是錯的
            
            $output['code'] = 420;
        }
    }
}
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
