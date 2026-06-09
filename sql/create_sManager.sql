-- Tạo user và cấp quyền cho cả localhost và IP 127.0.0.1
DROP USER IF EXISTS 'sManager'@'localhost';
DROP USER IF EXISTS 'sManager'@'127.0.0.1';

CREATE USER 'sManager'@'localhost' IDENTIFIED BY 'sManager@123';
CREATE USER 'sManager'@'127.0.0.1' IDENTIFIED BY 'sManager@123';

-- Cấp toàn quyền trên mọi database (để tránh lỗi tên DB không khớp)
GRANT ALL PRIVILEGES ON *.* TO 'sManager'@'localhost';
GRANT ALL PRIVILEGES ON *.* TO 'sManager'@'127.0.0.1';

FLUSH PRIVILEGES;