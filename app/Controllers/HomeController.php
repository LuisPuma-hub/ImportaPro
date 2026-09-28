<?php
class HomeController {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function index() {
        $stmt = $this->pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 20");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/../Views/layout/header.php';
        include __DIR__ . '/../Views/home.php';
        include __DIR__ . '/../Views/layout/footer.php';
    }

    public function show($id) {
        if (!$id) { http_response_code(404); echo "Producto no encontrado"; return; }
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => (int)$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$product) { http_response_code(404); echo "Producto no encontrado"; return; }
        include __DIR__ . '/../Views/layout/header.php';
        include __DIR__ . '/../Views/product.php';
        include __DIR__ . '/../Views/layout/footer.php';
    }
}