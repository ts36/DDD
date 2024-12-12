<?php
// 啟用 Session
session_start();

// 檢查是否登入
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
    <style>
        body {
            background-color: #fff7f0;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .table {
            margin-top: 20px;
        }
        .btn-remove {
            color: white;
            background-color: #ff6f61;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .btn-remove:hover {
            background-color: #e55b4d;
        }
        .summary {
            margin-top: 20px;
            text-align: right;
        }
        .summary h4 {
            font-weight: bold;
        }
        .btn-back {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center">購物車內容</h1>
    <?php if (empty($cart_items)): ?>
        <p class="text-center text-danger">您的購物車目前沒有任何商品，快去挑選甜點吧！</p>
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-back">繼續選購</a>
        </div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>品項名稱</th>
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
                        <td>$<?= number_format($item['price'], 2); ?></td>
                        <td>
                            <!-- 顯示數量，並允許用戶修改 -->
                            <form action="cart_update.php" method="POST" class="d-flex align-items-center">
                                <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['cart_id']); ?>">
                                <input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']); ?>" class="form-control" style="width: 70px;" min="1" required>
                                <button type="submit" class="btn btn-primary btn-sm ms-2">更新</button>
                            </form>
                        </td>
                        <td>$<?= number_format($item['total'], 2); ?></td>
                        <td>
                            <!-- 移除商品 -->
                            <form action="cart_remove.php" method="POST">
                                <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['cart_id']); ?>">
                                <button type="submit" class="btn-remove">刪除</button>
                            </form>
                        </td>
                    </tr>
                    <?php $total_price += $item['total']; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <!-- 繼續選購按鈕 -->
            <a href="index.php" class="btn btn-back">繼續選購</a>
            <!-- 總計與結帳按鈕 -->
            <div class="summary">
                <h4>總計: $<?= number_format($total_price, 2); ?></h4>
                <button class="btn btn-success">前往結帳</button>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
