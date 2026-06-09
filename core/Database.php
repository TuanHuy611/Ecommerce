<?php
// core/Database.php

class Database
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        // Kiểm tra xem đã đăng nhập chưa
        if (!isset($_SESSION['db_user']) || !isset($_SESSION['db_pass'])) {
            header('Location: index.php?page=login');
            exit;
        }

        if (self::$pdo === null) {
            $config = require __DIR__ . '/../config/config.php';

            $serverName = $config['db_host']; // Thường là 'localhost'
            $dbName     = $config['db_name'];
            $user       = $_SESSION['db_user'];
            $pass       = $_SESSION['db_pass'];

            // --- SỬA ĐOẠN NÀY ---
            // Đổi từ sqlsrv (SQL Server) sang mysql (MySQL)
            $port = $config['db_port'] ?? '3307'; // Lấy port từ config, mặc định 3306
            $dsn = "mysql:host=$serverName;port=$port;dbname=$dbName;charset=utf8mb4";

            try {
                self::$pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false, // Nên thêm dòng này cho MySQL
                ]);
            } catch (PDOException $e) {
                // Ghi log lỗi (tùy chọn) nhưng không nên show chi tiết lỗi ra màn hình user
                $_SESSION['login_error'] = 'Lỗi kết nối MySQL: ' . $e->getMessage();
                header('Location: index.php?page=login');
                exit;
            }
        }

        return self::$pdo;
    }
}