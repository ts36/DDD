<?php
// 啟用 Session
session_start();

// 檢查是否登入
if (!isset($_SESSION['id'])) {
    echo "請先登入！";
    exit();
}

// 資料庫連線
include 'database.php';

// 顯示購物車內容
$user_id = $_SESSION['id'];

$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($cartItems as $item) {
    echo "商品: " . $item['product_name'] . " - 數量: " . $item['quantity'] . "<br>";
}
?>
