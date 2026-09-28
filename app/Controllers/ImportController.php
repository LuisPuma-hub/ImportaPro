<?php

class ImportController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
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
        
        $query = "SELECT * FROM imports";
        $params = [];
        
        // Si es cliente, filtrar solo sus importaciones
        if (!$isAdmin) {
            $query .= " WHERE client_name = :client_name";
            $params[':client_name'] = $user['display_name'];
            
            if ($status !== 'Todas') {
                $query .= " AND status = :status";
                $params[':status'] = $status;
            }
        } else {
            // Admin ve todas las importaciones
            if ($status !== 'Todas') {
                $query .= " WHERE status = :status";
                $params[':status'] = $status;
            }
        }
        
        $query .= " ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $imports = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Contadores según el rol
        if (!$isAdmin) {
            $clientName = $user['display_name'];
            $countAll = $this->countByClient($clientName);
            $countDelivered = $this->countByClientAndStatus($clientName, 'Importado');
            $countInTransit = $this->countByClientAndStatus($clientName, 'En Tránsito');
            $countCustoms = $this->countByClientAndStatus($clientName, 'Aduanas');
        } else {
            $countAll = $this->countAll();
            $countDelivered = $this->countImported();
            $countInTransit = $this->countTransit();
            $countCustoms = $this->countCustoms();
        }
        
        // Mostrar vista según el rol
        if ($isAdmin) {
            include __DIR__ . '/../Views/imports.php';
        } else {
            include __DIR__ . '/../Views/client_imports.php';
        }
    }

    private function countByClient($clientName) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as cnt FROM imports WHERE client_name = :client_name");
        $stmt->execute([':client_name' => $clientName]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
    }

    private function countByClientAndStatus($clientName, $status) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as cnt FROM imports WHERE client_name = :client_name AND status = :status");
        $stmt->execute([':client_name' => $clientName, ':status' => $status]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
    }

    public function countAll() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as cnt FROM imports");
        return $stmt->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
    }

    public function countImported() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as cnt FROM imports WHERE status = :status");
        $stmt->execute([':status' => 'Importado']);
        return $stmt->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
    }

    public function countTransit() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as cnt FROM imports WHERE status = :status");
        $stmt->execute([':status' => 'En Tránsito']);
        return $stmt->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
    }

    public function countCustoms() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as cnt FROM imports WHERE status = :status");
        $stmt->execute([':status' => 'Aduanas']);
        return $stmt->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
    }

    public function create() {
        include __DIR__ . '/../Views/new_import.php';
    }

    public function store() {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        
        $errors = [];
        if (empty($_POST['client_name'])) $errors[] = 'El nombre del cliente es requerido';
        if (empty($_POST['product_name'])) $errors[] = 'El nombre del producto es requerido';
        if (empty($_POST['quantity']) || $_POST['quantity'] < 1) $errors[] = 'La cantidad debe ser mayor a 0';
        
        if (!empty($errors)) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $errors[0]]);
                exit;
            }
            $_SESSION['errors'] = $errors;
            header('Location: ?action=new_import');
            exit;
        }
        
        // Generar ID único (IMP-YYYY-NNN)
        $year = date('Y');
        $lastId = $this->pdo->query("SELECT id FROM imports WHERE id LIKE 'IMP-$year-%' ORDER BY id DESC LIMIT 1")->fetch();
        $lastNum = 0;
        if ($lastId) {
            $lastNum = intval(substr($lastId['id'], -3));
        }
        $newNum = str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
        $newId = "IMP-$year-$newNum";
        
        $stmt = $this->pdo->prepare("INSERT INTO imports (id, client_name, product_name, quantity, status, progress, estimated_arrival) VALUES (:id, :client_name, :product_name, :quantity, :status, :progress, :estimated_arrival)");
        $result = $stmt->execute([
            ':id' => $newId,
            ':client_name' => $_POST['client_name'],
            ':product_name' => $_POST['product_name'],
            ':quantity' => $_POST['quantity'],
            ':status' => $_POST['status'] ?? 'Importado',
            ':progress' => $_POST['progress'] ?? 0,
            ':estimated_arrival' => $_POST['estimated_arrival'] ?? null
        ]);
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'id' => $newId]);
            exit;
        }
        
        $_SESSION['import_message'] = 'Importación creada correctamente';
        header('Location: ?action=imports');
        exit;
    }

    public function edit($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM imports WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $import = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$import) {
            http_response_code(404);
            echo "Importación no encontrada";
            exit;
        }
        
        include __DIR__ . '/../Views/edit_import.php';
    }

    public function update($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM imports WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $import = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$import) {
            http_response_code(404);
            echo "Importación no encontrada";
            exit;
        }
        
        $stmt = $this->pdo->prepare("UPDATE imports SET client_name = :client_name, product_name = :product_name, quantity = :quantity, estimated_arrival = :estimated_arrival WHERE id = :id");
        $result = $stmt->execute([
            ':id' => $id,
            ':client_name' => $_POST['client_name'] ?? $import['client_name'],
            ':product_name' => $_POST['product_name'] ?? $import['product_name'],
            ':quantity' => $_POST['quantity'] ?? $import['quantity'],
            ':estimated_arrival' => $_POST['estimated_arrival'] ?? $import['estimated_arrival']
        ]);
        
        $_SESSION['import_message'] = 'Importación actualizada correctamente';
        header('Location: ?action=imports');
        exit;
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM imports WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
        
        $_SESSION['import_message'] = 'Importación eliminada correctamente';
        header('Location: ?action=imports');
        exit;
    }

    public function get_import($id) {
        header('Content-Type: application/json');
        $stmt = $this->pdo->prepare("SELECT * FROM imports WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $import = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($import ?? []);
        exit;
    }

    public function update_import($id) {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        
        $stmt = $this->pdo->prepare("SELECT * FROM imports WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $import = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$import) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'No encontrada']);
                exit;
            }
            http_response_code(404);
            echo "Importación no encontrada";
            exit;
        }
        
        $stmt = $this->pdo->prepare("UPDATE imports SET client_name = :client_name, product_name = :product_name, quantity = :quantity, status = :status, progress = :progress, estimated_arrival = :estimated_arrival WHERE id = :id");
        $result = $stmt->execute([
            ':id' => $id,
            ':client_name' => $_POST['client_name'] ?? $import['client_name'],
            ':product_name' => $_POST['product_name'] ?? $import['product_name'],
            ':quantity' => $_POST['quantity'] ?? $import['quantity'],
            ':status' => $_POST['status'] ?? $import['status'],
            ':progress' => $_POST['progress'] ?? $import['progress'],
            ':estimated_arrival' => $_POST['estimated_arrival'] ?? $import['estimated_arrival']
        ]);
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
        
        $_SESSION['import_message'] = 'Importación actualizada correctamente';
        header('Location: ?action=imports');
        exit;
    }
}
