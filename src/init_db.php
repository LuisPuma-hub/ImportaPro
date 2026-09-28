<?php
require __DIR__ . '/db.php';
$config = require __DIR__ . '/config.php';
$pdo = getPDO();
$schema = file_get_contents(__DIR__ . '/../sql/schema.sql');
$pdo->exec($schema);

// Seed admin user if not exists (email: root@local, password: admin)
$check = $pdo->prepare("SELECT id FROM users WHERE email = :email");
$check->execute([':email' => 'root@local']);
if (!$check->fetch()) {
    $hash = password_hash('admin', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (google_id, email, username, password, display_name, is_admin) VALUES (NULL, :email, :username, :password, :display_name, 1)");
    $stmt->execute([
        ':email' => 'root@local',
        ':username' => 'root',
        ':password' => $hash,
        ':display_name' => 'root'
    ]);
    echo "Usuario admin creado: root@local (password: admin)\n";
}

// Seed client user if not exists (email: cliente@test.com, password: cliente123)
$checkClient = $pdo->prepare("SELECT id FROM users WHERE email = :email");
$checkClient->execute([':email' => 'cliente@test.com']);
if (!$checkClient->fetch()) {
    $hashClient = password_hash('cliente123', PASSWORD_DEFAULT);
    $stmtClient = $pdo->prepare("INSERT INTO users (google_id, email, username, password, display_name, is_admin) VALUES (NULL, :email, :username, :password, :display_name, 0)");
    $stmtClient->execute([
        ':email' => 'cliente@test.com',
        ':username' => 'cliente_test',
        ':password' => $hashClient,
        ':display_name' => 'Cliente Test'
    ]);
    echo "Usuario cliente creado: cliente@test.com (password: cliente123)\n";
}

// Seed sample products if none
$checkP = $pdo->query("SELECT COUNT(*) as cnt FROM products");
$c = $checkP->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
if ($c == 0) {
    $ins = $pdo->prepare("INSERT INTO products (title, slug, description, country, price_cents, stock, category_id) VALUES (:title, :slug, :desc, :country, :price, :stock, :category)");
    
    // Electrónica
    $ins->execute([':title' => 'iPhone 15 Pro Max', ':slug' => 'iphone-15-pro-max', ':desc' => 'Smartphone Apple con pantalla OLED 6.7". Procesador A17 Pro, cámara de 48MP. Perfecto para profesionales.', ':country' => 'China', ':price' => 119999, ':stock' => 25, ':category' => null]);
    $ins->execute([':title' => 'MacBook Pro 16" M3', ':slug' => 'macbook-pro-16-m3', ':desc' => 'Laptop profesional con chip M3 Max, 36GB RAM, SSD 1TB. Ideal para diseño y desarrollo.', ':country' => 'China', ':price' => 349999, ':stock' => 12, ':category' => null]);
    $ins->execute([':title' => 'Samsung Galaxy S24 Ultra', ':slug' => 'galaxy-s24-ultra', ':desc' => 'Smartphone flagship con pantalla QHD+ 6.8", cámara 200MP, batería 5000mAh. Batería de larga duración.', ':country' => 'Corea del Sur', ':price' => 109999, ':stock' => 30, ':category' => null]);
    $ins->execute([':title' => 'iPad Pro 12.9" M2', ':slug' => 'ipad-pro-12-9-m2', ':desc' => 'Tablet de 12.9" con chip M2, pantalla Liquid Retina XDR, perfecta para creativos.', ':country' => 'China', ':price' => 94999, ':stock' => 18, ':category' => null]);
    $ins->execute([':title' => 'Sony WH-1000XM5', ':slug' => 'sony-wh-1000xm5', ':desc' => 'Audífonos inalámbricos con cancelación de ruido activa, batería 30h, sonido premium.', ':country' => 'Japón', ':price' => 29999, ':stock' => 45, ':category' => null]);
    $ins->execute([':title' => 'Apple Watch Series 9', ':slug' => 'apple-watch-series-9', ':desc' => 'Reloj inteligente con pantalla Always-On, sensor de temperatura, resistencia al agua 50m.', ':country' => 'China', ':price' => 39999, ':stock' => 35, ':category' => null]);
    $ins->execute([':title' => 'Dell XPS 15 i9', ':slug' => 'dell-xps-15-i9', ':desc' => 'Laptop de 15" FHD+ con Intel i9, RTX 4090, 32GB RAM, SSD 1TB NVMe.', ':country' => 'Tailandia', ':price' => 249999, ':stock' => 8, ':category' => null]);
    $ins->execute([':title' => 'GoPro Hero 12', ':slug' => 'gopro-hero-12', ':desc' => 'Cámara de acción 4K 60fps, estabilización de 8 ejes, resistencia 33m bajo agua.', ':country' => 'China', ':price' => 39999, ':stock' => 20, ':category' => null]);
    
    // Accesorios
    $ins->execute([':title' => 'AirPods Pro 2ª Gen', ':slug' => 'airpods-pro-2-gen', ':desc' => 'Audífonos wireless con cancelación de ruido adaptativa, mejor audio espacial.', ':country' => 'China', ':price' => 24999, ':stock' => 50, ':category' => null]);
    $ins->execute([':title' => 'Magic Mouse 2', ':slug' => 'magic-mouse-2', ':desc' => 'Ratón wireless recargable para Mac, superficie Multi-Touch responsiva.', ':country' => 'China', ':price' => 7999, ':stock' => 40, ':category' => null]);
    $ins->execute([':title' => 'USB-C Hub 7-en-1', ':slug' => 'usb-c-hub-7-en-1', ':desc' => 'Adaptador USB-C con HDMI, USB 3.0, SD, microSD, cargador rápido 60W.', ':country' => 'China', ':price' => 3999, ':stock' => 60, ':category' => null]);
    $ins->execute([':title' => 'Cable Lightning 2m Certificado', ':slug' => 'cable-lightning-2m', ':desc' => 'Cable de carga rápida para iPhone/iPad, certificado MFi, garantía.', ':country' => 'China', ':price' => 2499, ':stock' => 100, ':category' => null]);
    $ins->execute([':title' => 'Power Bank 30000mAh', ':slug' => 'power-bank-30000mah', ':desc' => 'Batería externa para laptop y phone, carga rápida 65W, pantalla LED.', ':country' => 'China', ':price' => 4999, ':stock' => 80, ':category' => null]);
    $ins->execute([':title' => 'Soporte Ajustable para Laptop', ':slug' => 'soporte-laptop-ajustable', ':desc' => 'Soporte de aluminio para laptop de 11"-17", altura ajustable, antideslizante.', ':country' => 'Taiwán', ':price' => 2999, ':stock' => 70, ':category' => null]);
    
    // Periféricos
    $ins->execute([':title' => 'Logitech MX Master 3S', ':slug' => 'logitech-mx-master-3s', ':desc' => 'Ratón profesional con precisión 8K, rueda de desplazamiento electromagnética.', ':country' => 'China', ':price' => 9999, ':stock' => 35, ':category' => null]);
    $ins->execute([':title' => 'Teclado Mecánico RGB 104 Teclas', ':slug' => 'teclado-mecanico-rgb', ':desc' => 'Teclado gaming con switches mecánicos, retroiluminación RGB, resistente al agua.', ':country' => 'China', ':price' => 7999, ':stock' => 25, ':category' => null]);
    $ins->execute([':title' => 'Monitor LG 27" IPS 4K', ':slug' => 'monitor-lg-27-4k', ':desc' => 'Monitor profesional UltraFine 4K, 98% DCI-P3, ideal para edición de video.', ':country' => 'Corea del Sur', ':price' => 59999, ':stock' => 15, ':category' => null]);
    $ins->execute([':title' => 'Webcam Logitech C922 Pro', ':slug' => 'webcam-logitech-c922', ':desc' => 'Cámara web Full HD 1080p 30fps, micrófono digital incorporado, auto-focus.', ':country' => 'China', ':price' => 4999, ':stock' => 40, ':category' => null]);
    
    // Wearables y Accesorios Personales
    $ins->execute([':title' => 'Smartband Xiaomi Band 8', ':slug' => 'smartband-xiaomi-band-8', ':desc' => 'Pulsera inteligente con AMOLED, 16 modos deportivos, monitor de sueño, 14 días batería.', ':country' => 'China', ':price' => 1999, ':stock' => 90, ':category' => null]);
    $ins->execute([':title' => 'Funda Resistente iPhone 15 Pro', ':slug' => 'funda-iphone-15-pro', ':desc' => 'Funda protectora con protección contra caídas de 1m, bordes reforzados.', ':country' => 'China', ':price' => 1499, ':stock' => 150, ':category' => null]);
    
    echo "Productos de ejemplo añadidos (24 productos).\n";
}

// Seed sample providers if none
$checkProv = $pdo->query("SELECT COUNT(*) as cnt FROM providers");
$cp = $checkProv->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
if ($cp == 0) {
    $insp = $pdo->prepare("INSERT INTO providers (name, country, contact, products_count, rating) VALUES (:name, :country, :contact, :products_count, :rating)");
    $insp->execute([':name' => 'TechSupply China', ':country' => 'China', ':contact' => 'contact@techsupply.cn', ':products_count' => 45, ':rating' => 4.8]);
    $insp->execute([':name' => 'Japan Electronics Co.', ':country' => 'Japón', ':contact' => 'info@jelec.jp', ':products_count' => 32, ':rating' => 4.9]);
    $insp->execute([':name' => 'Korea Tech Solutions', ':country' => 'Corea del Sur', ':contact' => 'sales@ktsol.kr', ':products_count' => 28, ':rating' => 4.7]);
    echo "Proveedores de ejemplo añadidos.\n";
}

// Seed sample quotes if none
$checkQuotes = $pdo->query("SELECT COUNT(*) as cnt FROM quotes");
$cq = $checkQuotes->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
if ($cq == 0) {
    $insq = $pdo->prepare("INSERT INTO quotes (id, client_name, product_name, quantity, status, requested_date, created_at) VALUES (:id, :client_name, :product_name, :quantity, :status, :requested_date, :created_at)");
    $insq->execute([':id' => 'Q-2024-008', ':client_name' => 'Juan Pérez', ':product_name' => 'iPhone 15 Pro', ':quantity' => 10, ':status' => 'Pendiente', ':requested_date' => '2025-12-07', ':created_at' => date('Y-m-d H:i:s')]);
    $insq->execute([':id' => 'Q-2024-007', ':client_name' => 'María López', ':product_name' => 'MacBook Pro M3', ':quantity' => 3, ':status' => 'Pendiente', ':requested_date' => '2025-12-06', ':created_at' => date('Y-m-d H:i:s')]);
    $insq->execute([':id' => 'Q-2024-006', ':client_name' => 'Carlos García', ':product_name' => 'iPad Air', ':quantity' => 8, ':status' => 'Cotizado', ':requested_date' => '2025-12-05', ':created_at' => date('Y-m-d H:i:s')]);
    echo "Cotizaciones de ejemplo añadidas.\n";
}

// Seed sample imports if none
$checkImports = $pdo->query("SELECT COUNT(*) as cnt FROM imports");
$ci = $checkImports->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
if ($ci == 0) {
    $insi = $pdo->prepare("INSERT INTO imports (id, client_name, product_name, quantity, status, progress, estimated_arrival, created_at) VALUES (:id, :client_name, :product_name, :quantity, :status, :progress, :estimated_arrival, :created_at)");
    $insi->execute([':id' => 'IMP-2025-045', ':client_name' => 'Juan Pérez', ':product_name' => 'MacBook Pro M3 x5', ':quantity' => 5, ':status' => 'En Tránsito', ':progress' => 75, ':estimated_arrival' => '2025-12-15', ':created_at' => date('Y-m-d H:i:s')]);
    $insi->execute([':id' => 'IMP-2025-046', ':client_name' => 'María López', ':product_name' => 'Sony WH-1000XM5 x20', ':quantity' => 20, ':status' => 'Aduanas', ':progress' => 85, ':estimated_arrival' => '2025-12-18', ':created_at' => date('Y-m-d H:i:s')]);
    $insi->execute([':id' => 'IMP-2025-047', ':client_name' => 'Carlos García', ':product_name' => 'iPad Air x8', ':quantity' => 8, ':status' => 'Importado', ':progress' => 100, ':estimated_arrival' => '2025-12-10', ':created_at' => date('Y-m-d H:i:s')]);
    echo "Importaciones de ejemplo añadidas.\n";
}

// Seed sample payments if none
$checkPayments = $pdo->query("SELECT COUNT(*) as cnt FROM payments");
$cpy = $checkPayments->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
if ($cpy == 0) {
    $inspy = $pdo->prepare("INSERT INTO payments (id, client_name, order_id, amount_cents, method, status, payment_date, created_at) VALUES (:id, :client_name, :order_id, :amount_cents, :method, :status, :payment_date, :created_at)");
    
    // Diciembre 2025
    $inspy->execute([':id' => 'PAY-2024-050', ':client_name' => 'Juan Pérez', ':order_id' => 'ORD-2024-045', ':amount_cents' => 1585000, ':method' => 'Transferencia', ':status' => 'Pendiente', ':payment_date' => '2025-12-07', ':created_at' => '2025-12-07 10:30:00']);
    $inspy->execute([':id' => 'PAY-2024-049', ':client_name' => 'María López', ':order_id' => 'ORD-2024-044', ':amount_cents' => 1015000, ':method' => 'Transferencia', ':status' => 'Verificado', ':payment_date' => '2025-12-06', ':created_at' => '2025-12-06 14:20:00']);
    $inspy->execute([':id' => 'PAY-2024-048', ':client_name' => 'Carlos García', ':order_id' => 'ORD-2024-043', ':amount_cents' => 950000, ':method' => 'Tarjeta', ':status' => 'Verificado', ':payment_date' => '2025-12-05', ':created_at' => '2025-12-05 09:15:00']);
    
    // Noviembre 2025
    $inspy->execute([':id' => 'PAY-2024-047', ':client_name' => 'Juan Pérez', ':order_id' => 'ORD-2024-042', ':amount_cents' => 1980000, ':method' => 'Transferencia', ':status' => 'Verificado', ':payment_date' => '2025-11-28', ':created_at' => '2025-11-28 16:45:00']);
    $inspy->execute([':id' => 'PAY-2024-046', ':client_name' => 'Ana Martínez', ':order_id' => 'ORD-2024-041', ':amount_cents' => 1450000, ':method' => 'PayPal', ':status' => 'Verificado', ':payment_date' => '2025-11-22', ':created_at' => '2025-11-22 11:30:00']);
    $inspy->execute([':id' => 'PAY-2024-045', ':client_name' => 'María López', ':order_id' => 'ORD-2024-040', ':amount_cents' => 1650000, ':method' => 'Transferencia', ':status' => 'Verificado', ':payment_date' => '2025-11-15', ':created_at' => '2025-11-15 13:20:00']);
    
    // Octubre 2025
    $inspy->execute([':id' => 'PAY-2024-044', ':client_name' => 'Juan Pérez', ':order_id' => 'ORD-2024-039', ':amount_cents' => 1800000, ':method' => 'Transferencia', ':status' => 'Verificado', ':payment_date' => '2025-10-25', ':created_at' => '2025-10-25 10:00:00']);
    $inspy->execute([':id' => 'PAY-2024-043', ':client_name' => 'Carlos García', ':order_id' => 'ORD-2024-038', ':amount_cents' => 1200000, ':method' => 'Tarjeta', ':status' => 'Verificado', ':payment_date' => '2025-10-18', ':created_at' => '2025-10-18 15:30:00']);
    
    echo "Pagos de ejemplo añadidos.\n";
}

// Seed more imports for reports
$checkMoreImports = $pdo->query("SELECT COUNT(*) as cnt FROM imports WHERE id IN ('IMP-2025-044', 'IMP-2025-043', 'IMP-2025-042', 'IMP-2025-041')");
$cmi = $checkMoreImports->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
if ($cmi == 0) {
    $insi = $pdo->prepare("INSERT INTO imports (id, client_name, product_name, quantity, status, progress, estimated_arrival, created_at) VALUES (:id, :client_name, :product_name, :quantity, :status, :progress, :estimated_arrival, :created_at)");
    
    // Más importaciones para enriquecer reportes
    $insi->execute([':id' => 'IMP-2025-044', ':client_name' => 'Ana Martínez', ':product_name' => 'iPhone 15 Pro', ':quantity' => 15, ':status' => 'Importado', ':progress' => 100, ':estimated_arrival' => '2025-11-20', ':created_at' => '2025-11-18 08:00:00']);
    $insi->execute([':id' => 'IMP-2025-043', ':client_name' => 'Juan Pérez', ':product_name' => 'iPhone 15 Pro', ':quantity' => 12, ':status' => 'Importado', ':progress' => 100, ':estimated_arrival' => '2025-11-15', ':created_at' => '2025-11-12 10:30:00']);
    $insi->execute([':id' => 'IMP-2025-042', ':client_name' => 'Carlos García', ':product_name' => 'MacBook Pro M3', ':quantity' => 8, ':status' => 'Importado', ':progress' => 100, ':estimated_arrival' => '2025-10-28', ':created_at' => '2025-10-25 14:20:00']);
    $insi->execute([':id' => 'IMP-2025-041', ':client_name' => 'María López', ':product_name' => 'iPhone 15 Pro', ':quantity' => 10, ':status' => 'Importado', ':progress' => 100, ':estimated_arrival' => '2025-10-22', ':created_at' => '2025-10-18 09:45:00']);
    
    echo "Importaciones adicionales añadidas.\n";
}

// Seed default settings
$checkSettings = $pdo->query("SELECT COUNT(*) as cnt FROM settings");
$cs = $checkSettings->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
if ($cs == 0) {
    $inss = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
    $inss->execute([':key' => 'company_name', ':value' => 'ImportaPro']);
    $inss->execute([':key' => 'company_email', ':value' => 'info@importapro.com']);
    $inss->execute([':key' => 'company_phone', ':value' => '+1 234 567 8900']);
    $inss->execute([':key' => 'company_address', ':value' => '123 Business Ave, City']);
    $inss->execute([':key' => 'site_banner_text', ':value' => 'Importa productos del mundo con confianza']);
    $inss->execute([':key' => 'site_description', ':value' => 'Simplificamos el proceso de importación para que puedas enfocarte en hacer crecer tu negocio']);
    $inss->execute([':key' => 'bank_name', ':value' => 'Banco Nacional de Comercio']);
    $inss->execute([':key' => 'bank_account_holder', ':value' => 'ImportaPro S.A. de C.V.']);
    echo "Configuraciones por defecto añadidas.\n";
}

echo "Base de datos inicializada en: " . realpath($config['db_path']) . "\n";