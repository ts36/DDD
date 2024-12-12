<?php
session_start();
// 啟用 Session

// 引入資料庫連線
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 資料驗證
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // 檢查是否為空
    if (empty($email) || empty($password)) {
        echo "<script>alert('請輸入電子郵件與密碼！'); window.location.href='login.html';</script>";
        exit();
    }

    try {
        // 查詢使用者資料 (檢查 email 和密碼)
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 密碼驗證
        if ($user && password_verify($password, $user['password'])) {
            // 登入成功，設置 Session
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];

            // 成功跳轉到首頁
            header("Location: index.php");
            echo "<script>window.location.href='index.php';</script>";
            exit();
        } else {
            echo "<script>alert('登入失敗，請檢查您的電子郵件與密碼！'); window.location.href='login.html';</script>";
            exit();
        }
    } catch (PDOException $e) {
        // 資料庫錯誤記錄並顯示錯誤
        file_put_contents("error_log.txt", date("Y-m-d H:i:s") . " - 資料庫錯誤: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
        die("資料庫錯誤，請聯繫管理員！");
    }
} else {
    echo "<script>alert('請使用表單提交資料！'); window.location.href='login.html';</script>";
    exit();
}
?>