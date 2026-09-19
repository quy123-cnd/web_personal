

<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "studio_db";

try {
    $conn = new PDO( "mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Kết nối database thất bại: " . $e->getMessage());
}

?>

