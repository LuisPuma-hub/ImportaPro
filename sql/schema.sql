-- Proveedores
CREATE TABLE IF NOT EXISTS providers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    contact TEXT,
    country TEXT,
    products_count INTEGER DEFAULT 0,
    rating REAL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
PRAGMA foreign_keys = ON;

-- Usuarios con soporte de contraseña local
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

-- Categorías (opcional)
CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    slug TEXT
);

-- Productos
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

-- Cotizaciones
CREATE TABLE IF NOT EXISTS quotes (
    id TEXT PRIMARY KEY,
    client_name TEXT NOT NULL,
    product_name TEXT NOT NULL,
    quantity INTEGER DEFAULT 1,
    status TEXT DEFAULT 'Pendiente',
    requested_date TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Órdenes de Importación
CREATE TABLE IF NOT EXISTS imports (
    id TEXT PRIMARY KEY,
    client_name TEXT NOT NULL,
    product_name TEXT NOT NULL,
    quantity INTEGER DEFAULT 1,
    status TEXT DEFAULT 'Importado',
    progress INTEGER DEFAULT 0,
    estimated_arrival TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Pagos
CREATE TABLE IF NOT EXISTS payments (
    id TEXT PRIMARY KEY,
    client_name TEXT NOT NULL,
    order_id TEXT NOT NULL,
    amount_cents INTEGER NOT NULL DEFAULT 0,
    method TEXT DEFAULT 'Transferencia',
    status TEXT DEFAULT 'Pendiente',
    payment_date TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Configuración del Sitio
CREATE TABLE IF NOT EXISTS settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    setting_key TEXT UNIQUE NOT NULL,
    setting_value TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Indices
CREATE INDEX IF NOT EXISTS idx_products_category ON products(category_id);
CREATE INDEX IF NOT EXISTS idx_quotes_status ON quotes(status);
CREATE INDEX IF NOT EXISTS idx_imports_status ON imports(status);
CREATE INDEX IF NOT EXISTS idx_payments_status ON payments(status);
CREATE INDEX IF NOT EXISTS idx_settings_key ON settings(setting_key);