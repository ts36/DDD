<?php
// 啟用 Session
session_start();

// 資料庫連線
include 'database.php';

// 獲取購物車 ID
$cart_id = $_POST['cart_id'] ?? null;

if (!$cart_id) {
    echo "<script>alert('無法刪除商品，請重試！'); window.location.href='cart.php';</script>";
    exit();
}

try {
    // 刪除購物車商品
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = :cart_id AND user_id = :user_id");
    $stmt->execute([
        'cart_id' => $cart_id,
        'user_id' => $_SESSION['id']
    ]);
    echo "<script>alert('商品已成功刪除！'); window.location.href='cart.php';</script>";
} catch (PDOException $e) {
    file_put_contents("error_log.txt", date("Y-m-d H:i:s") . " - 刪除購物車錯誤: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    die("刪除商品失敗，請聯繫管理員！");
}
?>
