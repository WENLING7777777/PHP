<?php
require './parts/connect_db.php';

$exhibition_id = isset($_GET['exhibition_id']) ? strval($_GET['exhibition_id']) : 0;

if (!empty($exhibition_id)) {
    // 删除关联的展览照片
    $delete_photos_sql = "DELETE FROM exhibition_photo WHERE exhibition_id = :exhibition_id";
    $delete_photos_stmt = $pdo->prepare($delete_photos_sql);
    $delete_photos_stmt->bindParam(':exhibition_id', $exhibition_id, PDO::PARAM_STR);

    if ($delete_photos_stmt->execute()) {
        // 如果展览照片删除成功，然后删除展览记录
        $delete_exhibition_sql = "DELETE FROM exhibition WHERE exhibition_id = :exhibition_id";
        $delete_exhibition_stmt = $pdo->prepare($delete_exhibition_sql);
        $delete_exhibition_stmt->bindParam(':exhibition_id', $exhibition_id, PDO::PARAM_STR);

        if ($delete_exhibition_stmt->execute()) {
            // 成功删除展览和相关照片
            $come_from = 'exhibition-list.php';
        } else {
            // 处理删除展览失败的情况
            // 可能添加错误处理逻辑
        }
    } else {
        // 处理删除展览照片失败的情况
        // 可能添加错误处理逻辑
    }
}

if (empty($come_from)) {
    if (!empty($_SERVER['HTTP_REFERER'])) {
        $come_from = $_SERVER['HTTP_REFERER'];
    }
}

header("Location: $come_from");
?>
