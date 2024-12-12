<?php
// database.php
$servername = "qoo.database.windows.net";
$username = "tsou36";
$password = "Aa123456";
$dbname = "DB";

try {
    $conn = new PDO("sqlsrv:Server=$servername;Database=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "連接失敗: " . $e->getMessage();
    die();
}
?>

