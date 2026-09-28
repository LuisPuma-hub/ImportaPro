<?php
class ProviderController {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // AJAX: obtener proveedor
    public function get_provider($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM providers WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $provider = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($provider ?: []);
        exit;
    }

    // AJAX / POST: actualizar proveedor
    public function update_provider($id) {
        header('Content-Type: application/json');
        $id = $id ?? ($_POST['id'] ?? null);
        $name = trim($_POST['name'] ?? '');
        $contact = trim($_POST['contact'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $products_count = $_POST['products_count'] ?? 0;
        $rating = $_POST['rating'] ?? 0;

        if (!$id || !$name) {
            echo json_encode(['success' => false, 'error' => 'Nombre e ID son requeridos.']);
            exit;
        }

        $products_count = (int)$products_count;
        $rating = (float)$rating;

        try {
            $stmt = $this->pdo->prepare("UPDATE providers SET name = :name, contact = :contact, country = :country, products_count = :products_count, rating = :rating WHERE id = :id");
            $ok = $stmt->execute([
                ':name' => $name,
                ':contact' => $contact,
                ':country' => $country,
                ':products_count' => $products_count,
                ':rating' => $rating,
                ':id' => $id
            ]);
            echo json_encode(['success' => $ok]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Error del servidor: '.$e->getMessage()]);
        }
        exit;
    }

    public function create() {
        include __DIR__ . '/../Views/new_provider.php';
    }

    public function store() {
        $name = trim($_POST['name'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $contact = trim($_POST['contact'] ?? '');
        $products_count = $_POST['products_count'] ?? 0;
        $rating = $_POST['rating'] ?? 0;
        $isAjax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && stripos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

        if (!$name) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'El nombre del proveedor es requerido.']);
                exit;
            }
            $_SESSION['validation_error'] = 'El nombre del proveedor es requerido.';
            header('Location: ?action=new_provider');
            exit;
        }

        $products_count = (int)$products_count;
        $rating = (float)$rating;

        try {
            $stmt = $this->pdo->prepare("INSERT INTO providers (name, country, contact, products_count, rating) VALUES (:name, :country, :contact, :products_count, :rating)");
            $ok = $stmt->execute([
                ':name' => $name,
                ':country' => $country,
                ':contact' => $contact,
                ':products_count' => $products_count,
                ':rating' => $rating
            ]);
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => $ok]);
                exit;
            }
            header('Location: ?action=providers');
            exit;
        } catch (Exception $e) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Error del servidor: '.$e->getMessage()]);
                exit;
            }
            $_SESSION['validation_error'] = 'Error del servidor al crear el proveedor.';
            header('Location: ?action=new_provider');
            exit;
        }
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM providers WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $_SESSION['provider_message'] = 'Proveedor eliminado correctamente.';
        header('Location: ?action=providers');
        exit;
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $where = '';
        $params = [];
        if ($search) {
            $where = "WHERE name LIKE :search OR country LIKE :search OR contact LIKE :search";
            $params[':search'] = "%$search%";
        }
        $stmt = $this->pdo->prepare("SELECT * FROM providers $where ORDER BY created_at DESC");
        $stmt->execute($params);
        $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/../Views/providers.php';
    }

    public function edit($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM providers WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $provider = $stmt->fetch(PDO::FETCH_ASSOC);
        include __DIR__ . '/../Views/edit_provider.php';
    }

    public function update($id) {
        $name = $_POST['name'] ?? '';
        $country = $_POST['country'] ?? '';
        $contact = $_POST['contact'] ?? '';
        $products_count = $_POST['products_count'] ?? 0;
        $rating = $_POST['rating'] ?? 0;
        $stmt = $this->pdo->prepare("UPDATE providers SET name = :name, country = :country, contact = :contact, products_count = :products_count, rating = :rating WHERE id = :id");
        $stmt->execute([
            ':name' => $name,
            ':country' => $country,
            ':contact' => $contact,
            ':products_count' => $products_count,
            ':rating' => $rating,
            ':id' => $id
        ]);
        header('Location: ?action=providers');
        exit;
    }
}

