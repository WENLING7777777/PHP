<?php
require './parts/connect_db.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // 首先從 stock 表中刪除相應的記錄
    $delete_stock_sql = "DELETE FROM stock WHERE product_id = :product_id";
    $delete_stock_stmt = $pdo->prepare($delete_stock_sql);
    $delete_stock_stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
    
    if ($delete_stock_stmt->execute()) {
        // 如果 stock 表中的記錄刪除成功，再刪除 product 表中的記錄
        $delete_product_photo_sql = "DELETE FROM product_photo WHERE product_id = :product_id";
        $delete_product_photo_stmt = $pdo->prepare($delete_product_photo_sql);
        $delete_product_photo_stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
    if ($delete_product_photo_stmt->execute()) {
        // 如果 stock 表中的記錄刪除成功，再刪除 product 表中的記錄
        $delete_product_sql = "DELETE FROM product WHERE product_id = :product_id";
        $delete_product_stmt = $pdo->prepare($delete_product_sql);
        $delete_product_stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
    }
        
        if ($delete_product_stmt->execute()) {
            $come_from = 'product-list.php';
            header("Location: $come_from"); 
            exit;
        } else {
            echo "刪除 product 失敗";
        }
    } else {
        echo "刪除 stock 失敗";
    }
} else {
    echo "ID 不存在";
}

