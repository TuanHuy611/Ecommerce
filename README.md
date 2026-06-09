# HƯỚNG DẪN CÀI ĐẶT VÀ CHẠY DỰ ÁN ECOMMERCE (PHP - MYSQL)

Đây là tài liệu hướng dẫn chi tiết cách thiết lập môi trường, cấu hình Database và chạy ứng dụng.

## 1. Yêu cầu hệ thống
* **XAMPP** (Phiên bản hỗ trợ PHP 8.0 trở lên).
* **Trình duyệt web:** Chrome, Firefox, hoặc Edge.
* **MySQL/MariaDB:** Đi kèm trong XAMPP.

## 2. Cài đặt thư mục dự án
1.  Copy thư mục dự án `eCommerce` vào thư mục `htdocs` của XAMPP.
    * Đường dẫn chuẩn: `C:\xampp\htdocs\eCommerce`

## 3. Cấu hình Cổng (Port) cho MySQL
**LƯU Ý QUAN TRỌNG:** Dự án này đang được cấu hình mặc định chạy trên **Port 3307** (để tránh xung đột cổng trên một số máy Windows).

### Cách kiểm tra Port của máy bạn:
1.  Mở **XAMPP Control Panel**.
2.  Nhìn vào dòng **MySQL**, cột **Port**.
    * Nếu là **3307**: Bạn không cần sửa gì cả.
    * Nếu là **3306** (Mặc định): Bạn cần sửa file cấu hình PHP (Xem mục 5).

## 4. Thiết lập Cơ sở dữ liệu (Database)
Để project chạy đúng, bạn cần Import các file SQL theo **đúng thứ tự** sau vào phpMyAdmin (http://localhost/phpmyadmin):

### Bước 1: Tạo bảng và dữ liệu mẫu
* Mở tab **SQL** trong phpMyAdmin.
* Copy toàn bộ nội dung file: `sql/create_insert_database.sql`
* Nhấn **Go** (Thực hiện).
* *Tác dụng:* Tạo database `ecommerce_support`, tạo bảng và chèn dữ liệu mẫu.

### Bước 2: Tạo Thủ tục, Hàm và Trigger
* Mở tab **SQL**.
* Copy toàn bộ nội dung file: `sql/procedures_functions_trigger.sql`
* Nhấn **Go**.
* *Tác dụng:* Thêm logic tính doanh thu, trigger tự động sinh mã và validate dữ liệu.

### Bước 3: Tạo User sManager và cấp quyền
* Mở tab **SQL**.
* Copy toàn bộ nội dung file: `sql/create_sManager.sql`
* Nhấn **Go**.
* *Tác dụng:* Tạo user `sManager` cho cả `localhost` và IP `127.0.0.1` để tránh lỗi kết nối.

## 5. Cấu hình kết nối PHP (Config)
Mở file `config/config.php` để đảm bảo thông số khớp với máy của bạn.

```php
<?php
return [
    'db_host' => '127.0.0.1',        // Khuyên dùng IP này thay vì localhost
    'db_name' => 'ecommerce_support',
    'db_charset' => 'utf8mb4',
    'db_port' => '3307'              // <--- SỬA Ở ĐÂY nếu XAMPP của bạn là 3306
];