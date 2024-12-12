<?php
// 啟用 Session
session_start();
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
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="#">🍰 可愛甜點店</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">首頁</a></li>
                <?php if (isset($_SESSION['id'])): ?>
                    <li class="nav-item"><span class="nav-link text-success">已登入: <?= htmlspecialchars($_SESSION['email']); ?></span></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">登出</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.html">購物車</a></li>
                    <li class="nav-item"><a class="nav-link" href="orders.html">訂單記錄</a></li>
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
        <!-- 商品卡片 (草莓蛋糕) -->
        <div class="col-md-4">
            <div class="card">
                <img src="https://via.placeholder.com/300" class="card-img-top" alt="甜點">
                <div class="card-body">
                    <h5 class="card-title">草莓蛋糕</h5>
                    <p class="card-text">價格: $150</p>
                    <?php if (isset($_SESSION['id'])): ?>
                        <form action="cart_process.php" method="POST">
                            <input type="hidden" name="product_id" value="1">
                            <input type="number" name="quantity" class="form-control mb-2" min="1" value="1" required>
                            <button type="submit" class="btn btn-custom w-100">加入購物車</button>
                        </form>
                    <?php else: ?>
                        <p class="text-danger">請先登入以加入購物車</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- 商品卡片 (巧克力布朗尼) -->
        <div class="col-md-4">
            <div class="card">
                <img src="https://via.placeholder.com/300" class="card-img-top" alt="甜點">
                <div class="card-body">
                    <h5 class="card-title">巧克力布朗尼</h5>
                    <p class="card-text">價格: $120</p>
                    <?php if (isset($_SESSION['id'])): ?>
                        <form action="cart_process.php" method="POST">
                            <input type="hidden" name="product_id" value="2">
                            <input type="number" name="quantity" class="form-control mb-2" min="1" value="1" required>
                            <button type="submit" class="btn btn-custom w-100">加入購物車</button>
                        </form>
                    <?php else: ?>
                        <p class="text-danger">請先登入以加入購物車</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- 商品卡片 (檸檬塔) -->
        <div class="col-md-4">
            <div class="card">
                <img src="https://via.placeholder.com/300" class="card-img-top" alt="甜點">
                <div class="card-body">
                    <h5 class="card-title">檸檬塔</h5>
                    <p class="card-text">價格: $130</p>
                    <?php if (isset($_SESSION['id'])): ?>
                        <form action="cart_process.php" method="POST">
                            <input type="hidden" name="product_id" value="3">
                            <input type="number" name="quantity" class="form-control mb-2" min="1" value="1" required>
                            <button type="submit" class="btn btn-custom w-100">加入購物車</button>
                        </form>
                    <?php else: ?>
                        <p class="text-danger">請先登入以加入購物車</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
