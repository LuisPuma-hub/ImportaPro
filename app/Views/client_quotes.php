<?php include __DIR__ . '/layout/header.php'; ?>
<!-- Client Quotes View -->
<main style="font-family:'Segoe UI','Roboto',Arial,sans-serif;background:#f7f9fb;padding:32px 20px;min-height:80vh;">
  <div style="max-width:1200px;margin:0 auto;">
    <!-- Header -->
    <div style="margin-bottom:28px;">
      <h1 style="margin:0 0 8px;font-size:1.8em;color:#111;">Mis Cotizaciones</h1>
      <p style="margin:0;color:#666;">Revisa y gestiona tus solicitudes de cotización</p>
    </div>

    <!-- Tabs -->
    <div style="display:flex;gap:8px;margin-bottom:24px;border-bottom:2px solid #e5e9f2;flex-wrap:wrap;">
      <?php 
      $tabs = [
        'Todas' => $countAll,
        'Pendientes' => $countPending,
        'Cotizadas' => $countQuoted,
        'Aprobadas' => $countApproved,
        'Rechazadas' => 0
      ];
      foreach ($tabs as $label => $count): 
        $isActive = ($status === $label);
        $tabStatus = $label === 'Cotizadas' ? 'Cotizado' : ($label === 'Aprobadas' ? 'Aprobado' : ($label === 'Pendientes' ? 'Pendiente' : $label));
      ?>
        <a href="?action=quotes&status=<?php echo urlencode($tabStatus); ?>" style="padding:12px 20px;text-decoration:none;color:<?php echo $isActive ? '#1769ff' : '#666'; ?>;font-weight:<?php echo $isActive ? '600' : '500'; ?>;border-bottom:<?php echo $isActive ? '3px solid #1769ff' : '3px solid transparent'; ?>;transition:all 0.2s;margin-bottom:-2px;">
          <?php echo $label; ?> (<?php echo $count; ?>)
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Empty State -->
    <?php if (empty($quotes)): ?>
      <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:64px 20px;text-align:center;">
        <div style="width:80px;height:80px;margin:0 auto 20px;background:#f0f4ff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:36px;">📄</div>
        <h3 style="margin:0 0 8px;font-size:1.3em;color:#111;">No hay cotizaciones</h3>
        <p style="margin:0 0 24px;color:#666;">Aún no has solicitado ninguna cotización</p>
        <a href="?action=products" style="display:inline-block;padding:12px 24px;background:#1769ff;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;box-shadow:0 4px 12px rgba(23,105,255,0.3);">
          Ver Catálogo de Productos
        </a>
      </div>
    <?php else: ?>
      <!-- Quotes List -->
      <div style="display:grid;gap:16px;">
        <?php 
        foreach ($quotes as $q):
          $statusColors = [
            'Pendiente' => ['bg' => '#fff4e6', 'color' => '#ff9800'],
            'Cotizado' => ['bg' => '#e3f2fd', 'color' => '#2196f3'],
            'Aprobado' => ['bg' => '#e8f5e9', 'color' => '#4caf50'],
            'Rechazado' => ['bg' => '#ffebee', 'color' => '#f44336']
          ];
          $statusStyle = $statusColors[$q['status']] ?? ['bg' => '#f5f5f5', 'color' => '#999'];
        ?>
          <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.04);transition:all 0.2s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'">
            <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:12px;flex-wrap:wrap;gap:12px;">
              <div style="flex:1;min-width:200px;">
                <div style="font-weight:700;font-size:1.1em;color:#111;margin-bottom:4px;"><?php echo htmlspecialchars($q['id']); ?></div>
                <div style="color:#666;font-size:0.95em;"><?php echo htmlspecialchars($q['product_name']); ?></div>
              </div>
              <span style="padding:6px 14px;background:<?php echo $statusStyle['bg']; ?>;color:<?php echo $statusStyle['color']; ?>;border-radius:6px;font-size:0.9em;font-weight:600;white-space:nowrap;">
                <?php echo $q['status']; ?>
              </span>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:16px;margin-bottom:12px;padding-top:12px;border-top:1px solid #f0f0f0;">
              <div>
                <div style="color:#999;font-size:0.85em;margin-bottom:2px;">Cantidad</div>
                <div style="color:#111;font-weight:600;"><?php echo $q['quantity']; ?> unidades</div>
              </div>
              <div>
                <div style="color:#999;font-size:0.85em;margin-bottom:2px;">Fecha de Solicitud</div>
                <div style="color:#111;font-weight:600;"><?php echo date('d/m/Y', strtotime($q['requested_date'])); ?></div>
              </div>
              <?php if (!empty($q['quoted_price'])): ?>
              <div>
                <div style="color:#999;font-size:0.85em;margin-bottom:2px;">Precio Cotizado</div>
                <div style="color:#1769ff;font-weight:700;font-size:1.15em;">$<?php echo number_format($q['quoted_price'], 2); ?></div>
              </div>
              <?php endif; ?>
            </div>

            <?php if ($q['status'] === 'Cotizado'): ?>
            <div style="display:flex;gap:8px;padding-top:12px;border-top:1px solid #f0f0f0;">
              <button style="flex:1;padding:10px 16px;background:#4caf50;color:#fff;border:none;border-radius:6px;font-weight:600;cursor:pointer;transition:background 0.2s;" onmouseover="this.style.background='#45a049'" onmouseout="this.style.background='#4caf50'">
                ✓ Aprobar Cotización
              </button>
              <button style="flex:1;padding:10px 16px;background:#fff;color:#f44336;border:1px solid #f44336;border-radius:6px;font-weight:600;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#fff0f3'" onmouseout="this.style.background='#fff'">
                ✕ Rechazar
              </button>
            </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</main>
<?php include __DIR__ . '/layout/footer.php'; ?>
