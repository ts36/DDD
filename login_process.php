<?php
// 啟用錯誤報告 (開發環境)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 資料庫連線
include 'database.php';

// 啟用 Session
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // 檢查是否為空
    if (empty($email) || empty($password)) {
        echo "<script>alert('請輸入電子郵件與密碼！'); window.location.href='login.html';</script>";
        exit();
    }

    try {
        // 查詢使用者資料
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // 登入成功，設置 Session
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // 成功跳轉到首頁
            echo "<script>alert('登入成功！即將跳轉到首頁'); window.location.href='index.html';</script>";
            exit();
        } else {
            echo "<script>alert('登入失敗，請檢查您的電子郵件與密碼！'); window.location.href='login.html';</script>";
            exit();
        }
    } catch (PDOException $e) {
        die("資料庫錯誤: " . $e->getMessage());
    }
} else {
    echo "<script>alert('請使用表單提交資料！'); window.location.href='login.html';</script>";
    exit();
}
?>
