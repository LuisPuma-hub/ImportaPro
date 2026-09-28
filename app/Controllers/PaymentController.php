<?php

class PaymentController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // Obtener usuario actual y determinar si es admin
        $isAdmin = !empty($_SESSION['user_id']);
        $user = null;
        
        if (!empty($_SESSION['user_id'])) {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute([':id' => $_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $isAdmin = !empty($user['is_admin']);
        }
        
        $status = $_GET['status'] ?? 'Todos';
        
        $query = "SELECT * FROM payments";
        $params = [];
        
        // Filtrar por cliente si no es admin
        if (!$isAdmin && $user) {
            $query .= " WHERE client_name = :client_name";
            $params[':client_name'] = $user['display_name'];
        }
        
        if ($status !== 'Todos') {
            $query .= ($isAdmin || !$user ? " WHERE" : " AND") . " status = :status";
            $params[':status'] = $status;
        }
        
        $query .= " ORDER BY payment_date DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calcular totales
        $totalPending = 0;
        $totalVerified = 0;
        $totalMonth = 0;
        
        foreach ($payments as $p) {
            $amount = $p['amount_cents'];
            if ($p['status'] === 'Pendiente') {
                $totalPending += $amount;
            } elseif ($p['status'] === 'Verificado') {
                $totalVerified += $amount;
            }
            $totalMonth += $amount;
        }
        
        // Contadores específicos del usuario
        if ($isAdmin) {
            $countAll = $this->countAll();
            $countPending = $this->countPending();
            $countVerified = $this->countVerified();
        } else {
            $countAll = $this->countByClient($user['display_name']);
            $countPending = $this->countByClientAndStatus($user['display_name'], 'Pendiente');
            $countVerified = $this->countByClientAndStatus($user['display_name'], 'Verificado');
        }
        
        // Variables para el cliente
        $balancePending = $totalPending;
        $balancePaid = $totalVerified;
        $invoiceCount = count($payments);
        
        // Renderizar vista apropiada
        if ($isAdmin) {
            include __DIR__ . '/../Views/payments.php';
        } else {
            include __DIR__ . '/../Views/client_payments.php';
        }
    }

    public function countAll() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as cnt FROM payments");
        return $stmt->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
    }

    public function countPending() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as cnt FROM payments WHERE status = :status");
        $stmt->execute([':status' => 'Pendiente']);
        return $stmt->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
    }

    public function countVerified() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as cnt FROM payments WHERE status = :status");
        $stmt->execute([':status' => 'Verificado']);
        return $stmt->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
    }

    public function countByClient($clientName) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as cnt FROM payments WHERE client_name = :client_name");
        $stmt->execute([':client_name' => $clientName]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
    }

    public function countByClientAndStatus($clientName, $status) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as cnt FROM payments WHERE client_name = :client_name AND status = :status");
        $stmt->execute([':client_name' => $clientName, ':status' => $status]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
    }

    public function create() {
        include __DIR__ . '/../Views/new_payment.php';
    }

    public function store() {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        
        $errors = [];
        if (empty($_POST['client_name'])) $errors[] = 'El nombre del cliente es requerido';
        if (empty($_POST['order_id'])) $errors[] = 'El ID de orden es requerido';
        if (empty($_POST['amount_cents']) || $_POST['amount_cents'] < 1) $errors[] = 'El monto debe ser mayor a 0';
        
        if (!empty($errors)) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $errors[0]]);
                exit;
            }
            $_SESSION['errors'] = $errors;
            header('Location: ?action=new_payment');
            exit;
        }
        
        // Generar ID único (PAY-YYYY-NNN)
        $year = date('Y');
        $lastId = $this->pdo->query("SELECT id FROM payments WHERE id LIKE 'PAY-$year-%' ORDER BY id DESC LIMIT 1")->fetch();
        $lastNum = 0;
        if ($lastId) {
            $lastNum = intval(substr($lastId['id'], -3));
        }
        $newNum = str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
        $newId = "PAY-$year-$newNum";
        
        $stmt = $this->pdo->prepare("INSERT INTO payments (id, client_name, order_id, amount_cents, method, status, payment_date) VALUES (:id, :client_name, :order_id, :amount_cents, :method, :status, :payment_date)");
        $result = $stmt->execute([
            ':id' => $newId,
            ':client_name' => $_POST['client_name'],
            ':order_id' => $_POST['order_id'],
            ':amount_cents' => $_POST['amount_cents'],
            ':method' => $_POST['method'] ?? 'Transferencia',
            ':status' => $_POST['status'] ?? 'Pendiente',
            ':payment_date' => $_POST['payment_date'] ?? null
        ]);
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'id' => $newId]);
            exit;
        }
        
        $_SESSION['payment_message'] = 'Pago creado correctamente';
        header('Location: ?action=payments');
        exit;
    }

    public function edit($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM payments WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$payment) {
            http_response_code(404);
            echo "Pago no encontrado";
            exit;
        }
        
        include __DIR__ . '/../Views/edit_payment.php';
    }

    public function update($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM payments WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$payment) {
            http_response_code(404);
            echo "Pago no encontrado";
            exit;
        }
        
        $stmt = $this->pdo->prepare("UPDATE payments SET client_name = :client_name, order_id = :order_id, amount_cents = :amount_cents, method = :method, payment_date = :payment_date WHERE id = :id");
        $result = $stmt->execute([
            ':id' => $id,
            ':client_name' => $_POST['client_name'] ?? $payment['client_name'],
            ':order_id' => $_POST['order_id'] ?? $payment['order_id'],
            ':amount_cents' => $_POST['amount_cents'] ?? $payment['amount_cents'],
            ':method' => $_POST['method'] ?? $payment['method'],
            ':payment_date' => $_POST['payment_date'] ?? $payment['payment_date']
        ]);
        
        $_SESSION['payment_message'] = 'Pago actualizado correctamente';
        header('Location: ?action=payments');
        exit;
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM payments WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
        
        $_SESSION['payment_message'] = 'Pago eliminado correctamente';
        header('Location: ?action=payments');
        exit;
    }

    public function get_payment($id) {
        header('Content-Type: application/json');
        $stmt = $this->pdo->prepare("SELECT * FROM payments WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($payment ?? []);
        exit;
    }

    public function update_payment($id) {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        
        $stmt = $this->pdo->prepare("SELECT * FROM payments WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$payment) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'No encontrado']);
                exit;
            }
            http_response_code(404);
            echo "Pago no encontrado";
            exit;
        }
        
        $stmt = $this->pdo->prepare("UPDATE payments SET client_name = :client_name, order_id = :order_id, amount_cents = :amount_cents, method = :method, status = :status, payment_date = :payment_date WHERE id = :id");
        $result = $stmt->execute([
            ':id' => $id,
            ':client_name' => $_POST['client_name'] ?? $payment['client_name'],
            ':order_id' => $_POST['order_id'] ?? $payment['order_id'],
            ':amount_cents' => $_POST['amount_cents'] ?? $payment['amount_cents'],
            ':method' => $_POST['method'] ?? $payment['method'],
            ':status' => $_POST['status'] ?? $payment['status'],
            ':payment_date' => $_POST['payment_date'] ?? $payment['payment_date']
        ]);
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
        
        $_SESSION['payment_message'] = 'Pago actualizado correctamente';
        header('Location: ?action=payments');
        exit;
    }

    public function verify($id) {
        $stmt = $this->pdo->prepare("UPDATE payments SET status = 'Verificado' WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
        
        $_SESSION['payment_message'] = 'Pago verificado correctamente';
        header('Location: ?action=payments');
        exit;
    }

    public function reject($id) {
        $stmt = $this->pdo->prepare("UPDATE payments SET status = 'Rechazado' WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
        
        $_SESSION['payment_message'] = 'Pago rechazado';
        header('Location: ?action=payments');
        exit;
    }
}
