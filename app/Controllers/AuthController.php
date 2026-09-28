<?php
class AuthController {
    private PDO $pdo;
    private array $config;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->config = require __DIR__ . '/../../src/config.php';
    }

    public function login() {
        include __DIR__ . '/../Views/login.php';
    }

    public function loginPost() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        if (!$email || !$password) {
            $_SESSION['flash_error'] = 'Email y contraseña son requeridos.';
            header('Location: ' . $this->config['base_url'] . '/?action=login');
            exit;
        }
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user || empty($user['password']) || !password_verify($password, $user['password'])) {
            $_SESSION['flash_error'] = 'Credenciales inválidas.';
            header('Location: ' . $this->config['base_url'] . '/?action=login');
            exit;
        }
        // Autenticar
        $_SESSION['user_id'] = $user['id'];
        header('Location: ' . $this->config['base_url'] . '/?action=dashboard');
        exit;
    }

    public function dashboard() {
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . $this->config['base_url'] . '/?action=login');
            exit;
        }
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => (int)$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        include __DIR__ . '/../Views/layout/header.php';
        
        // Mostrar dashboard según el rol del usuario
        if (!empty($user['is_admin'])) {
            include __DIR__ . '/../Views/dashboard.php';
        } else {
            include __DIR__ . '/../Views/client_dashboard.php';
        }
        
        include __DIR__ . '/../Views/layout/footer.php';
    }

    public function logout() {
        session_destroy();
        header('Location: ' . $this->config['base_url'] . '/?action=home');
        exit;
    }
    
    public function register() {
        include __DIR__ . '/../Views/register.php';
    }
    
    public function registerPost() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $account_type = $_POST['account_type'] ?? 'personal';
        $terms = $_POST['terms'] ?? '';
        
        // Validaciones
        if (!$name || !$email || !$phone || !$address || !$password || !$password_confirm) {
            $_SESSION['flash_error'] = 'Todos los campos son requeridos.';
            header('Location: ' . $this->config['base_url'] . '/?action=register');
            exit;
        }
        
        if ($password !== $password_confirm) {
            $_SESSION['flash_error'] = 'Las contraseñas no coinciden.';
            header('Location: ' . $this->config['base_url'] . '/?action=register');
            exit;
        }
        
        if (!$terms) {
            $_SESSION['flash_error'] = 'Debes aceptar los términos y condiciones.';
            header('Location: ' . $this->config['base_url'] . '/?action=register');
            exit;
        }
        
        // Verificar si el email ya existe
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $_SESSION['flash_error'] = 'El email ya está registrado.';
            header('Location: ' . $this->config['base_url'] . '/?action=register');
            exit;
        }
        
        // Crear usuario
        $username = explode('@', $email)[0];
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare("INSERT INTO users (email, username, password, display_name, is_admin) VALUES (:email, :username, :password, :display_name, 0)");
        $stmt->execute([
            ':email' => $email,
            ':username' => $username,
            ':password' => $hash,
            ':display_name' => $name
        ]);
        
        // Auto-login
        $userId = $this->pdo->lastInsertId();
        $_SESSION['user_id'] = $userId;
        
        header('Location: ' . $this->config['base_url'] . '/?action=dashboard');
        exit;
    }
}