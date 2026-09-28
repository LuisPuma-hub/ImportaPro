<?php
class UserController {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/../Views/users.php';
    }

    public function edit($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        include __DIR__ . '/../Views/edit_user.php';
    }

    public function update($id) {
        // Actualizar usuario (ejemplo básico)
        $display_name = $_POST['display_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? 'cliente';
        $stmt = $this->pdo->prepare("UPDATE users SET display_name = :display_name, email = :email, is_admin = :is_admin WHERE id = :id");
        $stmt->execute([
            ':display_name' => $display_name,
            ':email' => $email,
            ':is_admin' => ($role === 'admin' ? 1 : 0),
            ':id' => $id
        ]);
        header('Location: ?action=users');
        exit;
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header('Location: ?action=users');
        exit;
    }

    public function create() {
        include __DIR__ . '/../Views/new_user.php';
    }

    public function store() {
        $display_name = $_POST['display_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
        $role = $_POST['role'] ?? 'cliente';
        $stmt = $this->pdo->prepare("INSERT INTO users (display_name, email, password, is_admin) VALUES (:display_name, :email, :password, :is_admin)");
        $stmt->execute([
            ':display_name' => $display_name,
            ':email' => $email,
            ':password' => $password,
            ':is_admin' => ($role === 'admin' ? 1 : 0)
        ]);
        header('Location: ?action=users');
        exit;
    }
}
