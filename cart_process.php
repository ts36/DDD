<!-- cart_process.php -->
<?php
include 'database.php';
session_start();
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
    $stmt->execute([ 'user_id' => $user_id, 'product_id' => $product_id, 'quantity' => $quantity ]);

    echo "商品已加入購物車！";
}
?>

