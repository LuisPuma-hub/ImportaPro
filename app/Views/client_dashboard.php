<!-- Client Dashboard -->
<main style="font-family:'Segoe UI','Roboto',Arial,sans-serif;background:#f7f9fb;padding:32px 20px;min-height:80vh;">
  <div style="max-width:1200px;margin:0 auto;">
    <!-- Bienvenida -->
    <div style="margin-bottom:32px;">
      <h1 style="margin:0 0 4px;font-size:1.8em;color:#111;">Bienvenido, <?php echo htmlspecialchars($user['display_name'] ?? 'Cliente'); ?></h1>
      <p style="margin:0;color:#666;">Mi Empresa S.A.</p>
    </div>

    <!-- Stats Cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px;margin-bottom:32px;">
      <?php
      // Obtener cotizaciones del cliente
      $stmtQuotes = $this->pdo->prepare("SELECT COUNT(*) as total FROM quotes WHERE client_name = :name");
      $stmtQuotes->execute([':name' => $user['display_name']]);
      $quotesCount = $stmtQuotes->fetchColumn();

      // Obtener pedidos en proceso
      $stmtOrders = $this->pdo->prepare("SELECT COUNT(*) as total FROM imports WHERE client_name = :name AND status != 'Importado'");
      $stmtOrders->execute([':name' => $user['display_name']]);
      $ordersInProcess = $stmtOrders->fetchColumn();

      // Obtener pedidos completados
      $stmtCompleted = $this->pdo->prepare("SELECT COUNT(*) as total FROM imports WHERE client_name = :name AND status = 'Importado'");
      $stmtCompleted->execute([':name' => $user['display_name']]);
      $ordersCompleted = $stmtCompleted->fetchColumn();

      // Obtener saldo pendiente
      $stmtBalance = $this->pdo->prepare("SELECT SUM(amount_cents) as total FROM payments WHERE client_name = :name AND status = 'Pendiente'");
      $stmtBalance->execute([':name' => $user['display_name']]);
      $balancePending = $stmtBalance->fetchColumn() ?? 0;

      $stats = [
        ['📄', 'Cotizaciones Activas', $quotesCount, '#e8f0ff', '#1769ff'],
        ['📦', 'Pedidos en Proceso', $ordersInProcess, '#fff4e6', '#ff9800'],
        ['✅', 'Pedidos Completados', $ordersCompleted, '#e8f8f4', '#00c853'],
        ['💵', 'Saldo Pendiente', '$' . number_format($balancePending / 100, 0), '#fff0f3', '#f44336'],
      ];
      
      foreach ($stats as [$icon, $label, $value, $bg, $color]): ?>
        <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:20px;display:flex;align-items:center;gap:16px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
          <div style="width:52px;height:52px;background:<?php echo $bg; ?>;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;"><?php echo $icon; ?></div>
          <div style="flex:1;">
            <div style="font-size:1.6em;font-weight:700;color:<?php echo $color; ?>;margin-bottom:2px;"><?php echo $value; ?></div>
            <div style="color:#666;font-size:0.9em;"><?php echo $label; ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Two Column Layout -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(480px,1fr));gap:24px;">
      <!-- Cotizaciones Recientes -->
      <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
          <h2 style="margin:0;font-size:1.2em;color:#111;">Cotizaciones Recientes</h2>
          <a href="?action=quotes" style="color:#1769ff;text-decoration:none;font-size:0.9em;">Ver todas</a>
        </div>
        <?php
        $stmtRecentQuotes = $this->pdo->prepare("SELECT * FROM quotes WHERE client_name = :name ORDER BY created_at DESC LIMIT 3");
        $stmtRecentQuotes->execute([':name' => $user['display_name']]);
        $recentQuotes = $stmtRecentQuotes->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($recentQuotes)): ?>
          <p style="color:#999;text-align:center;padding:20px;">No tienes cotizaciones recientes</p>
        <?php else:
          foreach ($recentQuotes as $q):
            $statusColors = ['Pendiente'=>'#ff9800','Cotizado'=>'#2196f3','Aprobado'=>'#4caf50'];
            $statusColor = $statusColors[$q['status']] ?? '#999'; ?>
            <div style="border-bottom:1px solid #f0f0f0;padding:12px 0;">
              <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:6px;">
                <div>
                  <div style="font-weight:600;color:#111;margin-bottom:4px;"><?php echo htmlspecialchars($q['id']); ?></div>
                  <div style="color:#666;font-size:0.9em;"><?php echo htmlspecialchars($q['product_name']); ?> x<?php echo $q['quantity']; ?></div>
                  <div style="color:#999;font-size:0.85em;margin-top:2px;"><?php echo date('Y-m-d', strtotime($q['requested_date'])); ?></div>
                </div>
                <span style="padding:4px 10px;background:<?php echo $statusColor; ?>15;color:<?php echo $statusColor; ?>;border-radius:6px;font-size:0.85em;font-weight:600;"><?php echo $q['status']; ?></span>
              </div>
              <?php if (!empty($q['quoted_price'])): ?>
                <div style="color:#1769ff;font-weight:600;font-size:1.05em;">$<?php echo number_format($q['quoted_price'], 0); ?></div>
              <?php endif; ?>
            </div>
          <?php endforeach;
        endif; ?>
      </div>

      <!-- Pedidos Activos -->
      <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
          <h2 style="margin:0;font-size:1.2em;color:#111;">Pedidos Activos</h2>
          <a href="?action=imports" style="color:#1769ff;text-decoration:none;font-size:0.9em;">Ver todos</a>
        </div>
        <?php
        $stmtActiveOrders = $this->pdo->prepare("SELECT * FROM imports WHERE client_name = :name ORDER BY created_at DESC LIMIT 2");
        $stmtActiveOrders->execute([':name' => $user['display_name']]);
        $activeOrders = $stmtActiveOrders->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($activeOrders)): ?>
          <p style="color:#999;text-align:center;padding:20px;">No tienes pedidos activos</p>
        <?php else:
          foreach ($activeOrders as $order):
            $statusColors = ['Importado'=>'#4caf50','En Tránsito'=>'#ff9800','Aduanas'=>'#9c27b0'];
            $statusColor = $statusColors[$order['status']] ?? '#999'; ?>
            <div style="border:1px solid #f0f0f0;border-radius:8px;padding:16px;margin-bottom:12px;">
              <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                <div style="font-weight:600;color:#111;"><?php echo htmlspecialchars($order['id']); ?></div>
                <span style="padding:4px 10px;background:<?php echo $statusColor; ?>15;color:<?php echo $statusColor; ?>;border-radius:6px;font-size:0.85em;font-weight:600;"><?php echo $order['status']; ?></span>
              </div>
              <div style="color:#666;font-size:0.9em;margin-bottom:8px;"><?php echo htmlspecialchars($order['product_name']); ?></div>
              <div style="margin-bottom:8px;">
                <div style="display:flex;justify-content:space-between;font-size:0.85em;color:#666;margin-bottom:4px;">
                  <span>Progreso</span>
                  <span style="font-weight:600;"><?php echo $order['progress']; ?>%</span>
                </div>
                <div style="background:#e0e0e0;height:6px;border-radius:3px;overflow:hidden;">
                  <div style="background:#1769ff;height:100%;width:<?php echo $order['progress']; ?>%;transition:width 0.3s;"></div>
                </div>
              </div>
            </div>
          <?php endforeach;
        endif; ?>
      </div>
    </div>

    <!-- Quick Actions -->
    <div style="background:linear-gradient(135deg,#1769ff 0%,#0a3fbf 100%);border-radius:10px;padding:32px;margin-top:32px;text-align:center;color:#fff;">
      <h2 style="margin:0 0 12px;font-size:1.5em;">Acciones Rápidas</h2>
      <p style="margin:0 0 24px;opacity:0.95;">Gestiona tus importaciones de manera eficiente</p>
      <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
        <a href="?action=products" style="display:inline-block;padding:12px 24px;background:#fff;color:#1769ff;border-radius:8px;text-decoration:none;font-weight:600;box-shadow:0 4px 12px rgba(0,0,0,0.15);">Ver Catálogo</a>
        <a href="?action=quotes" style="display:inline-block;padding:12px 24px;background:rgba(255,255,255,0.15);color:#fff;border:2px solid #fff;border-radius:8px;text-decoration:none;font-weight:600;">Solicitar Cotización</a>
        <a href="?action=payments" style="display:inline-block;padding:12px 24px;background:rgba(255,255,255,0.15);color:#fff;border:2px solid #fff;border-radius:8px;text-decoration:none;font-weight:600;">Ver Pagos</a>
      </div>
    </div>
  </div>
</main>
