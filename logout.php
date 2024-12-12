<?php
// 啟用 Session
session_start();

// 清除 Session 並跳轉到首頁
session_unset();
session_destroy();

// 跳轉回首頁
header("Location: index.html");
exit();
?>
