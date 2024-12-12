<?php
// register_process.php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($name) || empty($email) || empty($password)) {
        echo "所有欄位都是必填的！";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "無效的電子郵件格式！";
        exit;
    }

    // 檢查電子郵件是否已註冊
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);

    if ($stmt->rowCount() > 0) {
        echo "該電子郵件已被註冊！";
        exit;
    }

    // 加密密碼並插入資料庫
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    if ($stmt->execute(['name' => $name, 'email' => $email, 'password' => $hashedPassword])) {
        echo "註冊成功！請前往 <a href='login.html'>登入</a>";
    } else {
        echo "註冊失敗，請稍後再試！";
    }
}
?>
