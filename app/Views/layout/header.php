<!-- Header layout -->
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Mi E‑commerce</title>
  <link rel="stylesheet" href="/style.css">
  <style>
    .navbar:not(.navbar-full) {
      background: #fff !important;
      box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }
    
    .navbar:not(.navbar-full) .logo a {
      color: #1769ff !important;
    }
    
    .navbar:not(.navbar-full) .navbar-items a {
      color: #666;
    }
    
    .navbar:not(.navbar-full) .navbar-items a:hover {
      color: #1769ff;
    }
  </style>
</head>
<body>
  <header class="site-header">
        <?php if (!empty($_SESSION['user_id'])) { 
          // Obtener datos del usuario logueado
          global $pdo;
          $stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = :id");
          $stmtUser->execute([':id' => $_SESSION['user_id']]);
          $loggedUser = $stmtUser->fetch(PDO::FETCH_ASSOC);
          $isAdmin = !empty($loggedUser['is_admin']);
          $userName = $loggedUser['display_name'] ?? 'Usuario';
          $userRole = $isAdmin ? 'Administrador' : 'Cliente';
        ?>
          <nav class="navbar navbar-full">
            <div class="navbar-logo">
              <h1 class="logo"><a href="?action=home">ImportaPro</a></h1>
            </div>
            <div class="navbar-items">
              <a href="?action=dashboard"<?php if (($_GET['action'] ?? '') === 'dashboard') echo ' class="primary"'; ?>>Dashboard</a>
              <a href="?action=products"<?php if (($_GET['action'] ?? '') === 'products') echo ' class="primary"'; ?>>Productos</a>
              <?php if ($isAdmin): ?>
              <a href="?action=users"<?php if (($_GET['action'] ?? '') === 'users') echo ' class="primary"'; ?>>Usuarios</a>
              <a href="?action=providers"<?php if (($_GET['action'] ?? '') === 'providers') echo ' class="primary"'; ?>>Proveedores</a>
              <?php endif; ?>
              <a href="?action=quotes"<?php if (($_GET['action'] ?? '') === 'quotes') echo ' class="primary"'; ?>><?php echo $isAdmin ? 'Cotizaciones' : 'Mis Cotizaciones'; ?></a>
              <a href="?action=imports"<?php if (($_GET['action'] ?? '') === 'imports') echo ' class="primary"'; ?>><?php echo $isAdmin ? 'Importaciones' : 'Mis Pedidos'; ?></a>
              <a href="?action=payments"<?php if (($_GET['action'] ?? '') === 'payments') echo ' class="primary"'; ?>>Pagos</a>
              <a href="?action=support"<?php if (($_GET['action'] ?? '') === 'support') echo ' class="primary"'; ?>>Soporte</a>
              <?php if ($isAdmin): ?>
              <a href="?action=reports"<?php if (($_GET['action'] ?? '') === 'reports') echo ' class="primary"'; ?>>Reportes</a>
              <a href="?action=settings"<?php if (($_GET['action'] ?? '') === 'settings') echo ' class="primary"'; ?>>Configuración</a>
              <?php endif; ?>
            </div>
            <div class="navbar-user">
              <span><?php echo htmlspecialchars($userName); ?><br><span><?php echo $userRole; ?></span></span>
              <a href="?action=logout" style="color:#fff;text-decoration:none;margin-left:12px;padding:6px 12px;background:rgba(255,255,255,0.1);border-radius:6px;font-size:0.9em;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">Cerrar sesión</a>
            </div>
          </nav>
        <?php } else { ?>
          <nav class="navbar">
            <div class="navbar-logo">
              <h1 class="logo"><a href="?action=home">ImportaPro</a></h1>
            </div>
            <div class="navbar-items">
              <a href="?action=home">Inicio</a>
              <a href="?action=products">Catálogo</a>
                <a href="?action=about">Nosotros</a>
              <a href="?action=contact">Contacto</a>
            </div>
            <div class="navbar-user">
              <a href="?action=login" style="color:#1769ff;text-decoration:none;padding:8px 16px;border:1px solid #1769ff;border-radius:6px;font-size:0.95em;transition:all 0.2s;margin-right:8px;" onmouseover="this.style.background='#f0f4ff'" onmouseout="this.style.background='transparent'">Iniciar Sesión</a>
              <a href="?action=register" style="color:#fff;text-decoration:none;padding:8px 16px;background:#1769ff;border-radius:6px;font-size:0.95em;transition:background 0.2s;" onmouseover="this.style.background='#0055cc'" onmouseout="this.style.background='#1769ff'">Registrarse</a>
            </div>
          </nav>
        <?php } ?>
    </div>
  </header>