<?php
// 啟用 Session
session_start();

// 檢查使用者是否已登入
if (!isset($_SESSION['id'])) {
    echo "<script>alert('請先登入後查看購物車！'); window.location.href='login.html';</script>";
    exit();
}

// 資料庫連線
include 'database.php';

// 查詢購物車內容
try {
    $stmt = $conn->prepare("
        SELECT c.id AS cart_id, p.name, p.price, c.quantity, (p.price * c.quantity) AS total
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = :user_id
    ");
    $stmt->execute(['user_id' => $_SESSION['id']]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 測試查詢結果 (除錯)
    if (empty($cart_items)) {
        echo "購物車為空，或查詢結果未正確返回";
    }
} catch (PDOException $e) {
    die("資料庫錯誤: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購物車</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">購物車</h2>
    <?php if (empty($cart_items)): ?>
        <p class="text-center">您的購物車是空的，快去挑選甜點吧！</p>
    <?php else: ?>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>商品名稱</th>
                    <th>單價</th>
                    <th>數量</th>
                    <th>小計</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php $total_price = 0; ?>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']); ?></td>
                        <td>$<?= htmlspecialchars($item['price']); ?></td>
                        <td><?= htmlspecialchars($item['quantity']); ?></td>
                        <td>$<?= htmlspecialchars($item['total']); ?></td>
                        <td>
                            <form action="cart_remove.php" method="POST">
                                <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['cart_id']); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">刪除</button>
                            </form>
                        </td>
                    </tr>
                    <?php $total_price += $item['total']; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-end">
            <h4>總計: $<?= number_format($total_price, 2); ?></h4>
            <button class="btn btn-success">前往結帳</button>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
