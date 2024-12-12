<?php
// database.php
$servername = "your_server_name";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_database_name";

try {
    $conn = new PDO("sqlsrv:Server=$servername;Database=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "連接失敗: " . $e->getMessage();
    die();
}
?>

