<!-- orders_process.php -->
<?php
include 'database.php';
session_start();
$user_id = $_SESSION['user_id'];

$stmt->execute(['user_id' => $user_id]);
$orders = $stmt->fetchAll();
?>

