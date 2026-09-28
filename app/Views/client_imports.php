<?php include __DIR__ . '/layout/header.php'; ?>
<!-- Client Orders View -->
<main style="font-family:'Segoe UI','Roboto',Arial,sans-serif;background:#f7f9fb;padding:32px 20px;min-height:80vh;">
  <div style="max-width:1200px;margin:0 auto;">
    <!-- Header -->
    <div style="margin-bottom:28px;">
      <h1 style="margin:0 0 8px;font-size:1.8em;color:#111;">Mis Pedidos e Importaciones</h1>
      <p style="margin:0;color:#666;">Seguimiento completo de tus importaciones</p>
    </div>

    <!-- Tabs -->
    <div style="display:flex;gap:8px;margin-bottom:24px;border-bottom:2px solid #e5e9f2;flex-wrap:wrap;">
      <?php 
      $tabs = [
        'Todos' => $countAll,
        'En Tránsito' => $countInTransit,
        'En Aduanas' => $countCustoms,
        'Entregados' => $countDelivered
      ];
      foreach ($tabs as $label => $count): 
        $isActive = ($status === $label || ($status === 'Todas' && $label === 'Todos'));
        $tabStatus = $label === 'Todos' ? 'Todas' : $label;
      ?>
        <a href="?action=imports&status=<?php echo urlencode($tabStatus); ?>" style="padding:12px 20px;text-decoration:none;color:<?php echo $isActive ? '#1769ff' : '#666'; ?>;font-weight:<?php echo $isActive ? '600' : '500'; ?>;border-bottom:<?php echo $isActive ? '3px solid #1769ff' : '3px solid transparent'; ?>;transition:all 0.2s;margin-bottom:-2px;">
          <?php echo $label; ?> (<?php echo $count; ?>)
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Empty State -->
    <?php if (empty($imports)): ?>
      <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:64px 20px;text-align:center;">
        <div style="width:80px;height:80px;margin:0 auto 20px;background:#f0f4ff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:36px;">📦</div>
        <h3 style="margin:0 0 8px;font-size:1.3em;color:#111;">No hay pedidos</h3>
        <p style="margin:0 0 24px;color:#666;">Aún no tienes importaciones en proceso</p>
        <a href="?action=quotes" style="display:inline-block;padding:12px 24px;background:#1769ff;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;box-shadow:0 4px 12px rgba(23,105,255,0.3);">
          Solicitar Nueva Cotización
        </a>
      </div>
    <?php else: ?>
      <!-- Orders List -->
      <div style="display:grid;gap:20px;">
        <?php 
        foreach ($imports as $imp):
          $statusColors = [
            'En Tránsito' => ['bg' => '#fff4e6', 'color' => '#ff9800', 'icon' => '🚢'],
            'Aduanas' => ['bg' => '#f3e5f5', 'color' => '#9c27b0', 'icon' => '🛃'],
            'Importado' => ['bg' => '#e8f5e9', 'color' => '#4caf50', 'icon' => '✓']
          ];
          $statusStyle = $statusColors[$imp['status']] ?? ['bg' => '#f5f5f5', 'color' => '#999', 'icon' => '📦'];
        ?>
          <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
            <!-- Order Header -->
            <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:16px;flex-wrap:wrap;gap:12px;">
              <div style="flex:1;min-width:200px;">
                <div style="font-weight:700;font-size:1.15em;color:#111;margin-bottom:6px;"><?php echo htmlspecialchars($imp['id']); ?></div>
                <div style="color:#111;font-size:1.05em;font-weight:500;margin-bottom:4px;"><?php echo htmlspecialchars($imp['product_name']); ?></div>
                <div style="color:#666;font-size:0.9em;"><?php echo $imp['quantity']; ?> unidades</div>
              </div>
              <div style="text-align:right;">
                <span style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:<?php echo $statusStyle['bg']; ?>;color:<?php echo $statusStyle['color']; ?>;border-radius:8px;font-size:0.95em;font-weight:600;white-space:nowrap;">
                  <span><?php echo $statusStyle['icon']; ?></span>
                  <?php echo $imp['status']; ?>
                </span>
              </div>
            </div>

            <!-- Order Details -->
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-bottom:16px;padding:16px;background:#f7f9fb;border-radius:8px;">
              <div>
                <div style="color:#999;font-size:0.85em;margin-bottom:4px;">Ordenado</div>
                <div style="color:#111;font-weight:600;font-size:0.95em;"><?php echo date('d/m/Y', strtotime($imp['created_at'])); ?></div>
              </div>
              <div>
                <div style="color:#999;font-size:0.85em;margin-bottom:4px;">Estimado</div>
                <div style="color:#111;font-weight:600;font-size:0.95em;"><?php echo date('d/m/Y', strtotime($imp['estimated_arrival'])); ?></div>
              </div>
              <?php if (!empty($imp['tracking_number'])): ?>
              <div>
                <div style="color:#999;font-size:0.85em;margin-bottom:4px;">Tracking</div>
                <div style="color:#1769ff;font-weight:600;font-size:0.95em;font-family:monospace;"><?php echo htmlspecialchars($imp['tracking_number']); ?></div>
              </div>
              <?php endif; ?>
              <?php if (!empty($imp['total_amount'])): ?>
              <div>
                <div style="color:#999;font-size:0.85em;margin-bottom:4px;">Monto Total</div>
                <div style="color:#4caf50;font-weight:700;font-size:1.1em;">$<?php echo number_format($imp['total_amount'], 0); ?></div>
              </div>
              <?php endif; ?>
            </div>

            <!-- Progress Bar -->
            <div style="margin-bottom:16px;">
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <span style="color:#666;font-size:0.9em;font-weight:500;">Progreso del pedido</span>
                <span style="color:#1769ff;font-weight:700;font-size:1.05em;"><?php echo $imp['progress']; ?>%</span>
              </div>
              <div style="background:#e0e0e0;height:8px;border-radius:4px;overflow:hidden;position:relative;">
                <div style="background:linear-gradient(90deg,#1769ff 0%,#0a3fbf 100%);height:100%;width:<?php echo $imp['progress']; ?>%;transition:width 0.5s ease;box-shadow:0 2px 4px rgba(23,105,255,0.3);"></div>
              </div>
            </div>

            <!-- Actions -->
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
              <button style="flex:1;min-width:150px;padding:10px 16px;background:#1769ff;color:#fff;border:none;border-radius:6px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:background 0.2s;" onmouseover="this.style.background='#0055cc'" onmouseout="this.style.background='#1769ff'">
                <span>👁</span> Ver Detalles
              </button>
              <?php if ($imp['status'] !== 'Importado'): ?>
              <button style="flex:1;min-width:150px;padding:10px 16px;background:#fff;color:#666;border:1px solid #e5e9f2;border-radius:6px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:all 0.2s;" onmouseover="this.style.background='#f7f9fb';this.style.borderColor='#1769ff'" onmouseout="this.style.background='#fff';this.style.borderColor='#e5e9f2'">
                <span>📄</span> Documentos (<?php echo rand(2,5); ?>)
              </button>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</main>
<?php include __DIR__ . '/layout/footer.php'; ?>
