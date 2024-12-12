<?php
// 啟用 Session
session_start();

// 檢查是否登入
if (!isset($_SESSION['id'])) {
    echo "<script>alert('請先登入後再結帳！'); window.location.href='login.html';</script>";
    exit();
}

// 資料庫連線
include 'database.php';

try {
    // 開始交易 (Transaction)
    $conn->beginTransaction();

    // 計算購物車總金額
    $stmt = $conn->prepare("
        SELECT c.product_id, c.quantity, p.price, (c.quantity * p.price) AS subtotal
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = :user_id
    ");
    $stmt->execute(['user_id' => $_SESSION['id']]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cart_items)) {
        echo "<script>alert('購物車為空，無法結帳！'); window.location.href='cart.php';</script>";
        exit();
    }

    $total_price = 0;
    foreach ($cart_items as $item) {
        $total_price += $item['subtotal'];
    }

    // 插入訂單資料到 `orders` 表
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price) VALUES (:user_id, :total_price)");
    $stmt->execute([
        'user_id' => $_SESSION['id'],
        'total_price' => $total_price
    ]);

    // 獲取新生成的訂單 ID
    $order_id = $conn->lastInsertId();

    // 插入每個商品到 `order_details` 表
    $stmt = $conn->prepare("
        INSERT INTO order_details (order_id, product_id, quantity) 
        VALUES (:order_id, :product_id, :quantity)
    ");
    foreach ($cart_items as $item) {
        $stmt->execute([
            'order_id' => $order_id,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity']
        ]);
    }

    // 清空購物車
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['id']]);

    // 提交交易
    $conn->commit();

    // 跳轉到訂單記錄頁面
    echo "<script>alert('結帳成功！訂單已生成。'); window.location.href='orders.php';</script>";
} catch (PDOException $e) {
    // 如果發生錯誤，回滾交易
    $conn->rollBack();
    file_put_contents("error_log.txt", date("Y-m-d H:i:s") . " - 結帳錯誤: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    die("結帳失敗，請聯繫管理員！");
}
?>
