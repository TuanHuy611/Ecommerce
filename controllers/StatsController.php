<?php
// controllers/StatsController.php
require_once __DIR__ . '/../core/Database.php';

class StatsController
{
    public function index(): void
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['db_user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $year  = (int)($_POST['year']  ?? date('Y'));
        $month = (int)($_POST['month'] ?? date('m'));
        $result = null; // Khởi tạo mảng
        $error  = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($year < 2000 || $year > 2100 || $month < 1 || $month > 12) {
                $error = 'Năm hoặc tháng không hợp lệ.';
            } else {
                try {
                    $pdo = Database::getConnection();
                    
                    // --- SỬA ĐOẠN NÀY ---
                    // Cú pháp MySQL: CALL procedure_name(param1, param2)
                    $stmt = $pdo->prepare("CALL sp_GetMonthlyRevenue(?, ?)");
                    $stmt->execute([$year, $month]);
                    
                    $result = $stmt->fetch();
                    
                    // MySQL trả về statement khác sau khi gọi Procedure, cần closeCursor để reset
                    $stmt->closeCursor(); 

                } catch (PDOException $e) {
                    $error = 'Lỗi khi gọi stored procedure: ' . $e->getMessage();
                }
            }
        }

        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/stats/index.php';
        require __DIR__ . '/../views/layout/footer.php';
    }
}