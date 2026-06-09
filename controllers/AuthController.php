<?php
// controllers/AuthController.php
require_once __DIR__ . '/../core/Database.php';

class AuthController
{
    public function login(): void
    {
        if (!isset($_SESSION)) session_start();

        if (isset($_SESSION['db_user'])) {
            header('Location: index.php?page=products');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            // $password = trim($_POST['password'] ?? '');
            $password = 'sManager@123';

            if ($username !== 'sManager') {
                $_SESSION['login_error'] = 'Chỉ cho phép đăng nhập bằng tài khoản sManager.';
            } else {
                $config = require __DIR__ . '/../config/config.php';
                $server = '127.0.0.1';
                $dbName = $config['db_name'];
                $port   = $config['db_port'] ?? '3307';
                $dsn = "mysql:host=$server;port=$port;dbname=$dbName;charset=utf8mb4";

                try {
                    $pdo = new PDO($dsn, $username, $password, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]);

                    $_SESSION['db_user'] = $username;
                    $_SESSION['db_pass'] = $password;

                    header('Location: index.php?page=products');
                    exit;
                } catch (PDOException $e) {
                    // SỬA TẠM ĐỂ XEM LỖI:
                    die("Lỗi kết nối: " . $e->getMessage() . 
                        "<br>Đang thử kết nối tới: $server:$port");
                    
                    $_SESSION['login_error'] = 'Sai mật khẩu hoặc user chưa được tạo trên MySQL.';
                }
            }
        }

        $error = $_SESSION['login_error'] ?? '';
        unset($_SESSION['login_error']);

        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/auth/login.php';
        require __DIR__ . '/../views/layout/footer.php';
    }

    public function logout(): void
    {
        if (!isset($_SESSION)) session_start();
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
