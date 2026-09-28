<?php
class ProductController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // AJAX: obtener producto
    public function get_product($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($product ?: []);
        exit;
    }

    // AJAX / POST: actualizar producto
    public function update_product($id) {
        header('Content-Type: application/json');
        $id = $id ?? ($_POST['id'] ?? null);
        $title = trim($_POST['title'] ?? '');
        $category = $_POST['category_id'] ?? null;
        $country = trim($_POST['country'] ?? '');
        $price_cents = $_POST['price_cents'] ?? 0;
        $stock = $_POST['stock'] ?? 0;
        $description = trim($_POST['description'] ?? '');

        if (!$id || !$title || !is_numeric($price_cents) || !is_numeric($stock)) {
            echo json_encode(['success' => false, 'error' => 'Datos inválidos: título, id, precio y stock son requeridos.']);
            exit;
        }

        $price_cents = (int)$price_cents;
        $stock = (int)$stock;
        $category = ($category === '' || $category === null) ? null : (int)$category;

        try {
            $stmt = $this->pdo->prepare("UPDATE products SET title = :title, category_id = :category_id, country = :country, price_cents = :price_cents, stock = :stock, description = :description WHERE id = :id");
            $ok = $stmt->execute([
                ':title' => $title,
                ':category_id' => $category,
                ':country' => $country,
                ':price_cents' => $price_cents,
                ':stock' => $stock,
                ':description' => $description,
                ':id' => $id
            ]);
            echo json_encode(['success' => $ok]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Error del servidor: '.$e->getMessage()]);
        }
        exit;
    }

    public function create() {
        include __DIR__ . '/../Views/new_product.php';
    }

    public function delete($id) {
        error_log("DELETE PRODUCT: id=$id");
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
        $success = $stmt->execute([':id' => $id]);
        $rows = $stmt->rowCount();
        error_log("DELETE RESULT: success=$success, rows=$rows");
        $_SESSION['product_message'] = ($success && $rows > 0)
            ? "Producto eliminado correctamente (ID: $id, filas: $rows)."
            : "No se pudo eliminar el producto (ID: $id, filas: $rows).";
        header('Location: ?action=products');
        exit;
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 10;
        $where = '';
        $params = [];
        if ($search) {
            $where = "WHERE products.title LIKE :search OR categories.name LIKE :search";
            $params[':search'] = "%$search%";
        }
        $countStmt = $this->pdo->prepare("SELECT COUNT(*) FROM products LEFT JOIN categories ON products.category_id = categories.id $where");
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare("SELECT products.*, categories.name AS category_name FROM products LEFT JOIN categories ON products.category_id = categories.id $where ORDER BY products.created_at DESC LIMIT :limit OFFSET :offset");
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $validation_error = $_SESSION['validation_error'] ?? '';
        unset($_SESSION['validation_error']);
        
        // Determinar si es admin
        $isAdmin = false;
        if (!empty($_SESSION['user_id'])) {
            $stmt = $this->pdo->prepare("SELECT is_admin FROM users WHERE id = :id");
            $stmt->execute([':id' => $_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $isAdmin = !empty($user['is_admin']);
        }
        
        // Mostrar catálogo para no-logueados y clientes, vista admin para administradores
        if (empty($_SESSION['user_id']) || !$isAdmin) {
            include __DIR__ . '/../Views/catalog.php';
        } else {
            include __DIR__ . '/../Views/product.php';
        }
    }

    public function show($id) {
        $stmt = $this->pdo->prepare("SELECT products.*, categories.name AS category_name FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE products.id = :id");
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$product) {
            echo '<main class="container"><div class="error">Producto no encontrado.</div></main>';
            return;
        }
        include __DIR__ . '/../Views/product_detail.php';
    }

    public function store() {
        $title = trim($_POST['title'] ?? '');
        $price_cents = $_POST['price_cents'] ?? '';
        $stock = $_POST['stock'] ?? '';
        $category_id = $_POST['category_id'] ?? null;
        $country = trim($_POST['country'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $isAjax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && stripos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

        $valid = $title && is_numeric($price_cents) && is_numeric($stock);
        if (!$valid) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Datos inválidos: título, precio y stock son requeridos y numéricos.']);
                exit;
            }
            $_SESSION['validation_error'] = 'Datos inválidos: título, precio y stock son requeridos y numéricos.';
            header('Location: ?action=new_product');
            exit;
        }

        $price_cents = (int)$price_cents;
        $stock = (int)$stock;
        $category_id = ($category_id === '' || $category_id === null) ? null : (int)$category_id;

        try {
            $stmt = $this->pdo->prepare("INSERT INTO products (title, category_id, country, price_cents, stock, description) VALUES (:title, :category_id, :country, :price_cents, :stock, :description)");
            $ok = $stmt->execute([
                ':title' => $title,
                ':category_id' => $category_id,
                ':country' => $country,
                ':price_cents' => $price_cents,
                ':stock' => $stock,
                ':description' => $description
            ]);
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => $ok]);
                exit;
            }
            header('Location: ?action=products');
            exit;
        } catch (Exception $e) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Error del servidor: '.$e->getMessage()]);
                exit;
            }
            $_SESSION['validation_error'] = 'Error del servidor al crear el producto.';
            header('Location: ?action=new_product');
            exit;
        }
    }

    public function edit($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        include __DIR__ . '/../Views/edit_product.php';
    }

    public function update($id) {
        $title = $_POST['title'] ?? '';
        $category = $_POST['category_id'] ?? '';
        $country = $_POST['country'] ?? '';
        $price_cents = $_POST['price_cents'] ?? 0;
        $stock = $_POST['stock'] ?? 0;
        $description = $_POST['description'] ?? '';
        $stmt = $this->pdo->prepare("UPDATE products SET title = :title, category_id = :category_id, country = :country, price_cents = :price_cents, stock = :stock, description = :description WHERE id = :id");
        $stmt->execute([
            ':title' => $title,
            ':category_id' => $category,
            ':country' => $country,
            ':price_cents' => $price_cents,
            ':stock' => $stock,
            ':description' => $description,
            ':id' => $id
        ]);
        header('Location: ?action=products');
        exit;
    }
}
