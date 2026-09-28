<?php
session_start();
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/db.php';

spl_autoload_register(function($class){
    $paths = [__DIR__ . '/../app/Controllers/', __DIR__ . '/../app/Models/'];
    foreach ($paths as $p) {
        $file = $p . $class . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

$action = $_GET['action'] ?? 'home';
$pdo = getPDO();

// Crear tablas si no existen (ejecutar solo una vez)
$pdo->exec("PRAGMA foreign_keys = ON;
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    google_id TEXT UNIQUE,
    email TEXT UNIQUE,
    username TEXT UNIQUE,
    password TEXT,
    display_name TEXT,
    avatar TEXT,
    is_admin INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    slug TEXT
);
CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    slug TEXT UNIQUE,
    description TEXT,
    country TEXT,
    price_cents INTEGER NOT NULL DEFAULT 0,
    stock INTEGER DEFAULT 0,
    category_id INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS providers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    contact TEXT,
    country TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
");

            switch ($action) {
                                case 'get_product':
                                    $controller = new ProductController($pdo);
                                    $controller->get_product($_GET['id'] ?? null);
                                    break;
                                case 'get_provider':
                                    $controller = new ProviderController($pdo);
                                    $controller->get_provider($_GET['id'] ?? null);
                                    break;
                case 'home':
                    $controller = new HomeController($pdo);
                    $controller->index();
                    break;
                case 'product':
                    $controller = new HomeController($pdo);
                    $controller->show($_GET['id'] ?? null);
                    break;
                case 'login':
                    $controller = new AuthController($pdo);
                    $controller->login();
                    break;
                case 'login_post':
                    $controller = new AuthController($pdo);
                    $controller->loginPost();
                    break;
                case 'register':
                    $controller = new AuthController($pdo);
                    $controller->register();
                    break;
                case 'register_post':
                    $controller = new AuthController($pdo);
                    $controller->registerPost();
                    break;
                case 'dashboard':
                    $controller = new AuthController($pdo);
                    $controller->dashboard();
                    break;
                case 'users':
                    $controller = new UserController($pdo);
                    $controller->index();
                    break;
                case 'edit_user':
                    $controller = new UserController($pdo);
                    $controller->edit($_GET['id'] ?? null);
                    break;
                case 'update_user':
                    $controller = new UserController($pdo);
                    $controller->update($_POST['id'] ?? null);
                    break;
                case 'delete_user':
                    $controller = new UserController($pdo);
                    $controller->delete($_GET['id'] ?? null);
                    break;
                case 'new_user':
                    $controller = new UserController($pdo);
                    $controller->create();
                    break;
                case 'store_user':
                    $controller = new UserController($pdo);
                    $controller->store();
                    break;
                case 'products':
                    $controller = new ProductController($pdo);
                    $controller->index();
                    break;
                case 'about':
                    include __DIR__ . '/../app/Views/about.php';
                    break;
                case 'contact':
                    include __DIR__ . '/../app/Views/contact.php';
                    break;
                case 'providers':
                    $controller = new ProviderController($pdo);
                    $controller->index();
                    break;
                case 'edit_product':
                    $controller = new ProductController($pdo);
                    $controller->edit($_GET['id'] ?? null);
                    break;
                case 'update_product':
                    $controller = new ProductController($pdo);
                    $controller->update_product($_GET['id'] ?? ($_POST['id'] ?? null));
                    break;
                case 'delete_product':
                    $controller = new ProductController($pdo);
                    $controller->delete($_GET['id'] ?? null);
                    break;
                case 'new_product':
                    $controller = new ProductController($pdo);
                    $controller->create();
                    break;
                case 'create_product':
                case 'store_product':
                    $controller = new ProductController($pdo);
                    $controller->store();
                    break;
                case 'product_detail':
                    $controller = new ProductController($pdo);
                    $controller->show($_GET['id'] ?? null);
                    break;
                case 'new_provider':
                    $controller = new ProviderController($pdo);
                    $controller->create();
                    break;
                case 'store_provider':
                    $controller = new ProviderController($pdo);
                    $controller->store();
                    break;
                case 'update_provider':
                    $controller = new ProviderController($pdo);
                    $controller->update_provider($_GET['id'] ?? ($_POST['id'] ?? null));
                    break;
                case 'edit_provider':
                    $controller = new ProviderController($pdo);
                    $controller->edit($_GET['id'] ?? null);
                    break;
                case 'delete_provider':
                    $controller = new ProviderController($pdo);
                    $controller->delete($_GET['id'] ?? null);
                    break;
                case 'get_quote':
                    $controller = new QuoteController($pdo);
                    $controller->get_quote($_GET['id'] ?? null);
                    break;
                case 'quotes':
                    $controller = new QuoteController($pdo);
                    $controller->index();
                    break;
                case 'new_quote':
                    $controller = new QuoteController($pdo);
                    $controller->create();
                    break;
                case 'store_quote':
                    $controller = new QuoteController($pdo);
                    $controller->store();
                    break;
                case 'edit_quote':
                    $controller = new QuoteController($pdo);
                    $controller->edit($_GET['id'] ?? null);
                    break;
                case 'update_quote':
                    $controller = new QuoteController($pdo);
                    $controller->update_quote($_GET['id'] ?? ($_POST['id'] ?? null));
                    break;
                case 'delete_quote':
                    $controller = new QuoteController($pdo);
                    $controller->delete($_GET['id'] ?? null);
                    break;
                case 'get_import':
                    $controller = new ImportController($pdo);
                    $controller->get_import($_GET['id'] ?? null);
                    break;
                case 'imports':
                    $controller = new ImportController($pdo);
                    $controller->index();
                    break;
                case 'new_import':
                    $controller = new ImportController($pdo);
                    $controller->create();
                    break;
                case 'store_import':
                    $controller = new ImportController($pdo);
                    $controller->store();
                    break;
                case 'edit_import':
                    $controller = new ImportController($pdo);
                    $controller->edit($_GET['id'] ?? null);
                    break;
                case 'update_import':
                    $controller = new ImportController($pdo);
                    $controller->update_import($_GET['id'] ?? ($_POST['id'] ?? null));
                    break;
                case 'delete_import':
                    $controller = new ImportController($pdo);
                    $controller->delete($_GET['id'] ?? null);
                    break;
                case 'get_payment':
                    $controller = new PaymentController($pdo);
                    $controller->get_payment($_GET['id'] ?? null);
                    break;
                case 'payments':
                    $controller = new PaymentController($pdo);
                    $controller->index();
                    break;
                case 'new_payment':
                    $controller = new PaymentController($pdo);
                    $controller->create();
                    break;
                case 'store_payment':
                    $controller = new PaymentController($pdo);
                    $controller->store();
                    break;
                case 'edit_payment':
                    $controller = new PaymentController($pdo);
                    $controller->edit($_GET['id'] ?? null);
                    break;
                case 'update_payment':
                    $controller = new PaymentController($pdo);
                    $controller->update_payment($_GET['id'] ?? ($_POST['id'] ?? null));
                    break;
                case 'delete_payment':
                    $controller = new PaymentController($pdo);
                    $controller->delete($_GET['id'] ?? null);
                    break;
                case 'verify_payment':
                    $controller = new PaymentController($pdo);
                    $controller->verify($_GET['id'] ?? null);
                    break;
                case 'reject_payment':
                    $controller = new PaymentController($pdo);
                    $controller->reject($_GET['id'] ?? null);
                    break;
                case 'support':
                    if (empty($_SESSION['user_id'])) {
                        header('Location: ?action=login');
                        exit;
                    }
                    include __DIR__ . '/../app/Views/client_support.php';
                    break;
                case 'reports':
                    $controller = new ReportController($pdo);
                    $controller->index();
                    break;
                case 'export_report':
                    $controller = new ReportController($pdo);
                    $controller->export();
                    break;
                case 'settings':
                    $controller = new SettingsController($pdo);
                    $controller->index();
                    break;
                case 'update_settings':
                    $controller = new SettingsController($pdo);
                    $controller->update();
                    break;
                case 'logout':
                    $controller = new AuthController($pdo);
                    $controller->logout();
                    break;
                default:
                    http_response_code(404);
                    echo "Ruta no encontrada";
            }