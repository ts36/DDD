<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // 檢查是否為空
    if (empty($username) || empty($email) || empty($password)) {
        die("所有欄位都是必填的！");
    }

    // 檢查電子郵件格式
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("電子郵件格式無效！");
    }

    try {
        // 檢查電子郵件或使用者名稱是否重複
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
        $stmt->execute([
            'email' => $email,
            'username' => $username
        ]);

        if ($stmt->rowCount() > 0) {
            die("該電子郵件或使用者名稱已被註冊！");
        }

        // 加密密碼
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 插入資料到資料庫
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
        if ($stmt->execute([
            'username' => $username,
            'password' => $hashedPassword,
            'email' => $email
        ])) {
            echo "註冊成功！請前往 <a href='login.html'>登入</a>";
        } else {
            die("註冊失敗，請稍後再試！");
        }
    } catch (PDOException $e) {
        die("資料庫錯誤: " . $e->getMessage());
    }
}
?>
