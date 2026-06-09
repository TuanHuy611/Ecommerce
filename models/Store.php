<?php
// models/Store.php
require_once __DIR__ . '/../core/Database.php';

class Store
{
    public static function getAll(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT Store_ID, Name FROM Store ORDER BY Name");
        return $stmt->fetchAll();
    }
}
