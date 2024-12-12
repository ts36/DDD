<!-- login_process.php -->
<?php
// 資料庫連線
include 'database.php';

// 啟用 Session
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // 檢查是否為空
    if (empty($email) || empty($password)) {
        die("請輸入電子郵件和密碼！");
    }

    // 從資料庫中檢查使用者資料
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // 登入成功，設置 Session，改用 `id`
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // 跳轉到首頁
            header("Location: index.html");
            exit();
        } else {
            echo "<script>alert('登入失敗，請檢查您的電子郵件與密碼！'); window.location.href = 'login.html';</script>";
            exit();
        }
    } catch (PDOException $e) {
        die("資料庫錯誤: " . $e->getMessage());
    }
} else {
    echo "<script>alert('請使用表單提交資料！'); window.location.href = 'login.html';</script>";
    exit();
}
?>

