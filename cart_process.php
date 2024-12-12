<?php
// 資料庫連線
include 'database.php';
session_start();

// 確認登入狀態
if (!isset($_SESSION['id'])) {
    echo "<script>alert('請先登入以加入購物車！'); window.location.href='login.html';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity <= 0) {
        echo "<script>alert('數量無效，請重新選擇商品數量。'); history.back();</script>";
        exit();
    }

    // 檢查購物車內是否已經有此商品
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->execute([
        'user_id' => $_SESSION['id'],
        'product_id' => $product_id
    ]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        // 更新數量
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute([
            'quantity' => $quantity,
            'user_id' => $_SESSION['id'],
            'product_id' => $product_id
        ]);
    } else {
        // 加入新商品到購物車
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
        $stmt->execute([
            'user_id' => $_SESSION['id'],
            'product_id' => $product_id,
            'quantity' => $quantity
        ]);
    }

    echo "<script>alert('商品已成功加入購物車！'); window.location.href='cart.html';</script>";
    exit();
}
?>
