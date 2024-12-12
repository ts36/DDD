<?php
session_start();
include 'database.php';

if (!isset($_SESSION['id'])) {
    echo "<script>alert('請先登入！'); window.location.href='login.html';</script>";
    exit();
}

$cart_id = $_POST['cart_id'] ?? null;
$quantity = $_POST['quantity'] ?? null;

if (!$cart_id || !$quantity || $quantity <= 0) {
    echo "<script>alert('無效的數量或購物車 ID！'); window.location.href='cart.php';</script>";
    exit();
}

try {
    $stmt = $conn->prepare("UPDATE cart SET quantity = :quantity WHERE id = :cart_id AND user_id = :user_id");
    $stmt->execute([
        'quantity' => $quantity,
        'cart_id' => $cart_id,
        'user_id' => $_SESSION['id']
    ]);
    echo "<script>alert('商品數量已更新！'); window.location.href='cart.php';</script>";
} catch (PDOException $e) {
    file_put_contents("error_log.txt", date("Y-m-d H:i:s") . " - 更新購物車錯誤: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    die("更新商品數量失敗！");
}
?>
