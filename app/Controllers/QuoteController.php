<?php
class QuoteController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // AJAX: obtener cotización
    public function get_quote($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM quotes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $quote = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($quote ?: []);
        exit;
    }

    // AJAX / POST: actualizar cotización
    public function update_quote($id) {
        header('Content-Type: application/json');
        $id = $id ?? ($_POST['id'] ?? null);
        $client_name = trim($_POST['client_name'] ?? '');
        $product_name = trim($_POST['product_name'] ?? '');
        $quantity = $_POST['quantity'] ?? 0;
        $status = trim($_POST['status'] ?? 'Pendiente');
        $requested_date = trim($_POST['requested_date'] ?? '');

        if (!$id || !$client_name || !$product_name) {
            echo json_encode(['success' => false, 'error' => 'Cliente, producto e ID son requeridos.']);
            exit;
        }

        $quantity = (int)$quantity;

        try {
            $stmt = $this->pdo->prepare("UPDATE quotes SET client_name = :client_name, product_name = :product_name, quantity = :quantity, status = :status, requested_date = :requested_date WHERE id = :id");
            $ok = $stmt->execute([
                ':client_name' => $client_name,
                ':product_name' => $product_name,
                ':quantity' => $quantity,
                ':status' => $status,
                ':requested_date' => $requested_date,
                ':id' => $id
            ]);
            echo json_encode(['success' => $ok]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Error del servidor: '.$e->getMessage()]);
        }
        exit;
    }

    public function create() {
        include __DIR__ . '/../Views/new_quote.php';
    }

    public function store() {
        $client_name = trim($_POST['client_name'] ?? '');
        $product_name = trim($_POST['product_name'] ?? '');
        $quantity = $_POST['quantity'] ?? 0;
        $status = trim($_POST['status'] ?? 'Pendiente');
        $requested_date = trim($_POST['requested_date'] ?? date('Y-m-d'));
        $isAjax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && stripos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

        if (!$client_name || !$product_name) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Cliente y producto son requeridos.']);
                exit;
            }
            $_SESSION['validation_error'] = 'Cliente y producto son requeridos.';
            header('Location: ?action=quotes');
            exit;
        }

        $quantity = (int)$quantity;

        try {
            $stmt = $this->pdo->prepare("INSERT INTO quotes (client_name, product_name, quantity, status, requested_date) VALUES (:client_name, :product_name, :quantity, :status, :requested_date)");
            $ok = $stmt->execute([
                ':client_name' => $client_name,
                ':product_name' => $product_name,
                ':quantity' => $quantity,
                ':status' => $status,
                ':requested_date' => $requested_date
            ]);
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => $ok]);
                exit;
            }
            header('Location: ?action=quotes');
            exit;
        } catch (Exception $e) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Error del servidor: '.$e->getMessage()]);
                exit;
            }
            $_SESSION['validation_error'] = 'Error del servidor.';
            header('Location: ?action=quotes');
            exit;
        }
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM quotes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $_SESSION['quote_message'] = 'Cotización eliminada correctamente.';
        header('Location: ?action=quotes');
        exit;
    }

    public function index() {
        // Verificar si el usuario está logueado
        if (empty($_SESSION['user_id'])) {
            header('Location: ?action=login');
            exit;
        }

        // Obtener información del usuario
        $stmtUser = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmtUser->execute([':id' => $_SESSION['user_id']]);
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
        $isAdmin = !empty($user['is_admin']);

        $status = $_GET['status'] ?? 'Todas';
        $where = '';
        $params = [];

        // Si es cliente, filtrar solo sus cotizaciones
        if (!$isAdmin) {
            $where = "WHERE client_name = :client_name";
            $params[':client_name'] = $user['display_name'];
            
            if ($status !== 'Todas') {
                $where .= " AND status = :status";
                $params[':status'] = $status;
            }
        } else {
            // Admin ve todas las cotizaciones
            if ($status !== 'Todas') {
                $where = "WHERE status = :status";
                $params[':status'] = $status;
            }
        }

        $stmt = $this->pdo->prepare("SELECT * FROM quotes $where ORDER BY requested_date DESC");
        $stmt->execute($params);
        $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Contar por estado
        if (!$isAdmin) {
            $clientFilter = "WHERE client_name = :client_name";
            $countAll = $this->pdo->prepare("SELECT COUNT(*) as cnt FROM quotes $clientFilter");
            $countAll->execute([':client_name' => $user['display_name']]);
            $countAll = $countAll->fetch(PDO::FETCH_ASSOC)['cnt'];
            
            $countPending = $this->pdo->prepare("SELECT COUNT(*) as cnt FROM quotes $clientFilter AND status = 'Pendiente'");
            $countPending->execute([':client_name' => $user['display_name']]);
            $countPending = $countPending->fetch(PDO::FETCH_ASSOC)['cnt'];
            
            $countQuoted = $this->pdo->prepare("SELECT COUNT(*) as cnt FROM quotes $clientFilter AND status = 'Cotizado'");
            $countQuoted->execute([':client_name' => $user['display_name']]);
            $countQuoted = $countQuoted->fetch(PDO::FETCH_ASSOC)['cnt'];
            
            $countApproved = $this->pdo->prepare("SELECT COUNT(*) as cnt FROM quotes $clientFilter AND status = 'Aprobado'");
            $countApproved->execute([':client_name' => $user['display_name']]);
            $countApproved = $countApproved->fetch(PDO::FETCH_ASSOC)['cnt'];
        } else {
            $countAll = $this->pdo->query("SELECT COUNT(*) as cnt FROM quotes")->fetch(PDO::FETCH_ASSOC)['cnt'];
            $countPending = $this->pdo->query("SELECT COUNT(*) as cnt FROM quotes WHERE status = 'Pendiente'")->fetch(PDO::FETCH_ASSOC)['cnt'];
            $countQuoted = $this->pdo->query("SELECT COUNT(*) as cnt FROM quotes WHERE status = 'Cotizado'")->fetch(PDO::FETCH_ASSOC)['cnt'];
            $countApproved = $this->pdo->query("SELECT COUNT(*) as cnt FROM quotes WHERE status = 'Aprobado'")->fetch(PDO::FETCH_ASSOC)['cnt'];
        }

        // Mostrar vista según el rol
        if ($isAdmin) {
            include __DIR__ . '/../Views/quotes.php';
        } else {
            include __DIR__ . '/../Views/client_quotes.php';
        }
    }

    public function edit($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM quotes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $quote = $stmt->fetch(PDO::FETCH_ASSOC);
        include __DIR__ . '/../Views/edit_quote.php';
    }

    public function update($id) {
        $client_name = trim($_POST['client_name'] ?? '');
        $product_name = trim($_POST['product_name'] ?? '');
        $quantity = $_POST['quantity'] ?? 0;
        $status = trim($_POST['status'] ?? 'Pendiente');
        $requested_date = trim($_POST['requested_date'] ?? '');

        $stmt = $this->pdo->prepare("UPDATE quotes SET client_name = :client_name, product_name = :product_name, quantity = :quantity, status = :status, requested_date = :requested_date WHERE id = :id");
        $stmt->execute([
            ':client_name' => $client_name,
            ':product_name' => $product_name,
            ':quantity' => $quantity,
            ':status' => $status,
            ':requested_date' => $requested_date,
            ':id' => $id
        ]);
        header('Location: ?action=quotes');
        exit;
    }
}
