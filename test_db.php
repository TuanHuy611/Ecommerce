<?php
// File: test_db.php

// CẤU HÌNH (Bắt buộc dùng 127.0.0.1 để MySQL nhận diện đúng Port 3307)
$host = '127.0.0.1';        
$db   = 'ecommerce_support';
$user = 'sManager';
$pass = 'sManager@123';
$port = '3307';      // Port của XAMPP máy bạn
$charset = 'utf8mb4';

// Chuỗi kết nối chuẩn
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "<h1 style='color:green'>✅ KẾT NỐI THÀNH CÔNG!</h1>";
     echo "Đã kết nối vào database '$db' trên cổng $port với user '$user'.";
} catch (\PDOException $e) {
     echo "<h1 style='color:red'>❌ KẾT NỐI THẤT BẠI</h1>";
     echo "<b>Lỗi chi tiết:</b> " . $e->getMessage();
}
?>