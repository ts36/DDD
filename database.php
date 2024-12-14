<?php
// database.php
$servername = "qoo.database.windows.net";
$username = "tsou36";
$password = "Aa123456";
$dbname = "DB";

try {
    $conn = new PDO("sqlsrv:Server=$servername;Database=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 確保傳入的資料使用 UTF-8
    $conn->exec("SET ANSI_NULLS ON");
    $conn->exec("SET ANSI_WARNINGS ON");
} catch (PDOException $e) {
    echo "連接失敗: " . $e->getMessage();
    die();
}
?>

