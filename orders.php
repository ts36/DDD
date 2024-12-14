<?php
// 啟用 Session
session_start();

// 檢查是否登入
if (!isset($_SESSION['id'])) {
    echo "<script>alert('請先登入後查看訂單記錄！'); window.location.href='login.html';</script>";
    exit();
}

// 資料庫連線
include 'database.php';

// 查詢訂單記錄
try {
    $stmt = $conn->prepare("
        SELECT 
            o.id AS order_id, 
            o.total_price, 
            o.created_at, 
            STRING_AGG(CONCAT(p.name, ' (x', od.quantity, ')'), ', ') AS products
        FROM orders o
        JOIN order_details od ON o.id = od.order_id
        JOIN products p ON od.product_id = p.id
        WHERE o.user_id = :user_id
        GROUP BY o.id, o.total_price, o.created_at
        ORDER BY o.created_at DESC
    ");
    $stmt->execute(['user_id' => $_SESSION['id']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("資料庫錯誤: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>訂單記錄</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff7f0;
            font-family: 'Comic Sans MS', 'Arial', sans-serif;
        }
        h2 {
            color: #ff6f61;
            font-weight: bold;
        }
        .table {
            border: 2px solid #ffcad4;
            border-radius: 10px;
            overflow: hidden;
        }
        .table thead {
            background-color: #ffcad4;
            color: #fff;
        }
        .table tbody tr {
            background-color: #ffe4e1;
        }
        .table tbody tr:hover {
            background-color: #ffd1d1;
        }
        .btn-back {
            background-color: #ff6f61;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            font-size: 16px;
        }
        .btn-back:hover {
            background-color: #ff3b2f;
            color: white;
        }
        .container {
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            padding: 20px;
            background-color: #fff;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">🍓 訂單記錄 🍓</h2>
    <?php if (empty($orders)): ?>
        <p class="text-center text-danger">目前沒有訂單記錄。</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>訂單編號</th>
                    <th>商品</th>
                    <th>總金額</th>
                    <th>日期</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']); ?></td>
                        <td><?= htmlspecialchars($order['products']); ?></td>
                        <td>$<?= number_format($order['total_price'], 2); ?></td>
                        <td><?= htmlspecialchars($order['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- 繼續選購按鈕 -->
    <div class="text-center mt-4">
        <a href="index.php" class="btn-back">🍰 回到首頁繼續選購</a>
    </div>
</div>
</body>
</html>
