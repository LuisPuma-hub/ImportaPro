<?php
class Product {
    public static function all(PDO $pdo) {
        $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}