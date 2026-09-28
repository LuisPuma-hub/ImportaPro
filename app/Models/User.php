<?php
class User {
    public static function findById(PDO $pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}