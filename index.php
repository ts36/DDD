<?php
// 啟用錯誤報告 (開發階段)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 啟用 Session
session_start();

// 清理瀏覽器快取 (避免上一頁問題)
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0");

// 資料庫連線
include 'database.php';

// 查詢商品資料
try {
    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("資料庫錯誤: " . $e->getMessage());
}

// 檢查購物車數量
$cartCount = 0;
if (isset($_SESSION['id'])) {
    try {
        $stmt = $conn->prepare("SELECT SUM(quantity) AS total FROM cart WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $_SESSION['id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $cartCount = $result['total'] ?? 0;
    } catch (PDOException $e) {
        file_put_contents("error_log.txt", date("Y-m-d H:i:s") . " - 購物車數量錯誤: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>可愛甜點店</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff7f0;
            font-family: 'Arial', sans-serif;
        }
        .navbar {
            background-color: #ffcad4;
        }
        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
        }
        .card {
            margin: 15px;
            border: none;
            border-radius: 15px;
            background-color: #ffe4e1;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
        }
        .btn-custom {
            background-color: #ff6f61;
            color: white;
            border-radius: 25px;
        }
        .cart-badge {
            background-color: #ff6f61;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">🍰 可愛甜點店</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">首頁</a></li>
                <?php if (isset($_SESSION['id'])): ?>
                    <li class="nav-item">
                        <span class="nav-link text-success">歡迎: <?= htmlspecialchars($_SESSION['username']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">登出</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.html">購物車 
                            <span class="cart-badge"><?= $cartCount ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.html">訂單記錄</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.html">登入 / 註冊</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1 class="text-center">🍓 精選甜點 🍓</h1>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4">
                <div class="card">
                    <img src="<?= htmlspecialchars($product['image_url']); ?>" class="card-img-top" alt="甜點">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text">價格: $<?= htmlspecialchars($product['price']); ?></p>
                        
                        <?php if (isset($_SESSION['id'])): ?>
                            <form action="cart_process.php" method="POST">
                                <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                <input type="number" name="quantity" class="form-control mb-2" min="1" value="1" required>
                                <button type="submit" class="btn btn-custom w-100">加入購物車</button>
                            </form>
                        <?php else: ?>
                            <p class="text-danger">請先登入以加入購物車</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
