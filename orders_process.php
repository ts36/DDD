<!-- orders_process.php -->
<?php
include 'database.php';
session_start();
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_date DESC");
$stmt->execute(['user_id' => $user_id]);
$orders = $stmt->fetchAll();
?>

