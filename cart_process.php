<?php
// 啟用 Session
session_start();

// 資料庫連線
include 'database.php';

// 檢查使用者是否登入
if (!isset($_SESSION['id'])) {
    echo "<script>alert('請先登入後再添加商品到購物車！'); window.location.href='login.html';</script>";
    exit();
}

// 獲取商品 ID 和數量
$product_id = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? null;

if (empty($product_id) || empty($quantity)) {
    echo "<script>alert('請選擇商品並輸入數量！'); window.location.href='index.php';</script>";
    exit();
}

try {
    // 確認 product_id 是否存在於 products 表中
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :product_id");
    $stmt->execute(['product_id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "<script>alert('商品不存在，請重新選擇！'); window.location.href='index.php';</script>";
        exit();
    }

    // 檢查購物車中是否已存在該商品
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->execute([
        'user_id' => $_SESSION['id'],
        'product_id' => $product_id
    ]);
    $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_item) {
        // 更新已有商品數量
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute([
            'quantity' => $quantity,
            'user_id' => $_SESSION['id'],
            'product_id' => $product_id
        ]);
    } else {
        // 插入新商品
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
        $stmt->execute([
            'user_id' => $_SESSION['id'],
            'product_id' => $product_id,
            'quantity' => $quantity
        ]);
    }

    echo "<script>alert('商品已成功加入購物車！'); window.location.href='cart.php';</script>";
} catch (PDOException $e) {
    file_put_contents("error_log.txt", date("Y-m-d H:i:s") . " - 加入購物車錯誤: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    die("加入購物車失敗，請聯繫管理員！");
}
?>
