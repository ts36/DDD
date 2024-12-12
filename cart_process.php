<?php

// 啟用 Session
session_start();

// 引入資料庫連線
include 'database.php';

// 檢查是否登入
if (!isset($_SESSION['id'])) {
    echo "<script>alert('請先登入以加入購物車！'); window.location.href='login.html';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity <= 0) {
        echo "<script>alert('商品數量無效，請重新選擇！'); history.back();</script>";
        exit();
    }

    try {
        // 檢查購物車是否已有此商品
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute([
            'user_id' => $_SESSION['id'],
            'product_id' => $product_id
        ]);

        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            // 更新購物車數量
            $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id");
            $stmt->execute([
                'quantity' => $quantity,
                'user_id' => $_SESSION['id'],
                'product_id' => $product_id
            ]);
        } else {
            // 新增購物車項目
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
            $stmt->execute([
                'user_id' => $_SESSION['id'],
                'product_id' => $product_id,
                'quantity' => $quantity
            ]);
        }

        echo "<script>alert('商品已成功加入購物車！'); window.location.href='cart.html';</script>";
        exit();
    } catch (PDOException $e) {
        die("資料庫錯誤: " . $e->getMessage());
    }
}
?>
