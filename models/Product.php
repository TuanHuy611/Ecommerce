<?php
// models/Product.php
require_once __DIR__ . '/../core/Database.php';

class Product
{
    public static function getAll(array $filters = []): array
    {
        $pdo = Database::getConnection();

        $keyword = $filters['keyword'] ?? '';
        $storeId = $filters['store_id'] ?? '';
        $sort    = $filters['sort'] ?? 'name_asc';

        $orderBySql = match ($sort) {
            'price_asc'  => 'p.Price ASC',
            'price_desc' => 'p.Price DESC',
            'name_desc'  => 'p.Name DESC',
            default      => 'p.Name ASC',
        };

        $where  = [];
        $params = [];

        if ($keyword !== '') {
            $where[] = 'p.Name LIKE :kw';
            $params[':kw'] = '%' . $keyword . '%';
        }

        if ($storeId !== '') {
            $where[] = 'p.Store_ID = :sid';
            $params[':sid'] = $storeId;
        }

        $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

        $sql = "
            SELECT p.Product_ID, p.Name, p.Price, p.Status, p.Stock_quantity,
                   s.Name AS StoreName
            FROM Product p
            INNER JOIN Store s ON p.Store_ID = s.Store_ID
            $whereSql
            ORDER BY $orderBySql
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM Product WHERE Product_ID = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): void
    {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO Product
                (Store_ID, Name, Description, Price, Images_url, Stock_quantity, Status)
                VALUES (:store_id, :name, :desc, :price, :img, :stock, :status)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':store_id' => $data['Store_ID'],
            ':name'     => $data['Name'],
            ':desc'     => $data['Description'],
            ':price'    => $data['Price'],
            ':img'      => $data['Images_url'],
            ':stock'    => $data['Stock_quantity'],
            ':status'   => $data['Status'],
        ]);
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::getConnection();
        $sql = "UPDATE Product
                SET Store_ID = :store_id,
                    Name = :name,
                    Description = :desc,
                    Price = :price,
                    Images_url = :img,
                    Stock_quantity = :stock,
                    Status = :status
                WHERE Product_ID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':store_id' => $data['Store_ID'],
            ':name'     => $data['Name'],
            ':desc'     => $data['Description'],
            ':price'    => $data['Price'],
            ':img'      => $data['Images_url'],
            ':stock'    => $data['Stock_quantity'],
            ':status'   => $data['Status'],
            ':id'       => $id,
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM Product WHERE Product_ID = ?");
        $stmt->execute([$id]);
    }

    public static function validate(array $data): array
    {
        $errors = [];

        if (empty($data['Store_ID']) || (int)$data['Store_ID'] <= 0) {
            $errors[] = 'Vui lòng chọn cửa hàng.';
        }

        if (trim($data['Name'] ?? '') === '') {
            $errors[] = 'Tên sản phẩm không được để trống.';
        }

        if (!isset($data['Price']) || (float)$data['Price'] <= 0) {
            $errors[] = 'Giá sản phẩm phải lớn hơn 0.';
        }

        if ((int)($data['Stock_quantity'] ?? 0) < 0) {
            $errors[] = 'Tồn kho không được âm.';
        }

        $validStatuses = ['Available', 'Out of Stock', 'Discontinued'];
        if (!in_array($data['Status'] ?? '', $validStatuses, true)) {
            $errors[] = 'Trạng thái sản phẩm không hợp lệ.';
        }

        return $errors;
    }
}
