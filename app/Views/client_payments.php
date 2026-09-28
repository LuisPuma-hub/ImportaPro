<?php include __DIR__ . '/layout/header.php'; ?>
<!-- Client Payments View -->
<main style="font-family:'Segoe UI','Roboto',Arial,sans-serif;background:#f7f9fb;padding:32px 20px;min-height:80vh;">
  <div style="max-width:1200px;margin:0 auto;">
    <!-- Header -->
    <div style="margin-bottom:28px;">
      <h1 style="margin:0 0 8px;font-size:1.8em;color:#111;">Pagos y Facturación</h1>
      <p style="margin:0;color:#666;">Gestiona tus pagos y descarga comprobantes</p>
    </div>

    <!-- Stats Cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:28px;">
      <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <div style="display:flex;align-items:center;gap:12px;">
          <div style="width:48px;height:48px;background:#ffebee;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;">⚠️</div>
          <div style="flex:1;">
            <div style="color:#999;font-size:0.85em;margin-bottom:2px;">Saldo Pendiente</div>
            <div style="color:#f44336;font-size:1.4em;font-weight:700;">$<?php echo number_format($balancePending / 100, 0); ?></div>
          </div>
        </div>
      </div>
      <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <div style="display:flex;align-items:center;gap:12px;">
          <div style="width:48px;height:48px;background:#e8f5e9;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;">✓</div>
          <div style="flex:1;">
            <div style="color:#999;font-size:0.85em;margin-bottom:2px;">Total Pagado</div>
            <div style="color:#4caf50;font-size:1.4em;font-weight:700;">$<?php echo number_format($balancePaid / 100, 0); ?></div>
          </div>
        </div>
      </div>
      <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <div style="display:flex;align-items:center;gap:12px;">
          <div style="width:48px;height:48px;background:#e3f2fd;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;">📄</div>
          <div style="flex:1;">
            <div style="color:#999;font-size:0.85em;margin-bottom:2px;">Facturas</div>
            <div style="color:#2196f3;font-size:1.4em;font-weight:700;"><?php echo $invoiceCount; ?></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Banking Information -->
    <div style="background:#e3f2fd;border:1px solid #90caf9;border-radius:10px;padding:24px;margin-bottom:28px;">
      <h3 style="margin:0 0 16px;font-size:1.1em;color:#1565c0;font-weight:600;">Datos Bancarios para Transferencias</h3>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;">
        <div>
          <div style="color:#666;font-size:0.85em;margin-bottom:6px;font-weight:500;">Banco</div>
          <div style="color:#111;font-size:1em;font-weight:600;">Banco Nacional de Comercio</div>
        </div>
        <div>
          <div style="color:#666;font-size:0.85em;margin-bottom:6px;font-weight:500;">Titular</div>
          <div style="color:#111;font-size:1em;font-weight:600;">ImportaPro S.A. de C.V.</div>
        </div>
        <div>
          <div style="color:#666;font-size:0.85em;margin-bottom:6px;font-weight:500;">Cuenta</div>
          <div style="color:#111;font-size:1em;font-weight:600;font-family:monospace;">1234-5678-9012-3456</div>
        </div>
        <div>
          <div style="color:#666;font-size:0.85em;margin-bottom:6px;font-weight:500;">CLABE</div>
          <div style="color:#111;font-size:1em;font-weight:600;font-family:monospace;">012345678901234567</div>
        </div>
      </div>
    </div>

    <!-- Empty State or Payments List -->
    <?php if (empty($payments)): ?>
      <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:64px 20px;text-align:center;">
        <div style="width:80px;height:80px;margin:0 auto 20px;background:#f0f4ff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:36px;">💳</div>
        <h3 style="margin:0 0 8px;font-size:1.3em;color:#111;">No hay pagos registrados</h3>
        <p style="margin:0;color:#666;">Tus pagos aparecerán aquí cuando tengas órdenes activas</p>
      </div>
    <?php else: ?>
      <h3 style="margin:0 0 16px;font-size:1.15em;color:#111;font-weight:600;">Historial de Pagos</h3>
      <div style="display:grid;gap:16px;">
        <?php 
        foreach ($payments as $p):
          $statusColors = [
            'Pendiente' => ['bg' => '#fff4e6', 'color' => '#ff9800', 'icon' => '⏳'],
            'Verificado' => ['bg' => '#e8f5e9', 'color' => '#4caf50', 'icon' => '✓'],
            'Rechazado' => ['bg' => '#ffebee', 'color' => '#f44336', 'icon' => '✕']
          ];
          $statusStyle = $statusColors[$p['status']] ?? ['bg' => '#f5f5f5', 'color' => '#999', 'icon' => '•'];
        ?>
          <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
            <div style="flex:1;min-width:200px;">
              <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                <div style="font-weight:700;color:#111;font-size:1.05em;"><?php echo htmlspecialchars($p['id']); ?></div>
                <span style="padding:4px 10px;background:<?php echo $statusStyle['bg']; ?>;color:<?php echo $statusStyle['color']; ?>;border-radius:6px;font-size:0.85em;font-weight:600;">
                  <?php echo $statusStyle['icon']; ?> <?php echo $p['status']; ?>
                </span>
              </div>
              <div style="color:#666;font-size:0.9em;margin-bottom:4px;">Orden: <span style="font-weight:600;color:#111;"><?php echo htmlspecialchars($p['order_id']); ?></span></div>
              <div style="color:#999;font-size:0.85em;">Método: <span style="color:#666;"><?php echo htmlspecialchars($p['method']); ?></span></div>
            </div>
            <div style="text-align:right;">
              <div style="color:#1769ff;font-size:1.4em;font-weight:700;margin-bottom:8px;">
                $<?php echo number_format(($p['amount_cents'] ?? 0) / 100, 2); ?>
              </div>
              <div style="color:#999;font-size:0.85em;"><?php echo date('d/m/Y', strtotime($p['payment_date'])); ?></div>
            </div>
            <?php if ($p['status'] === 'Verificado'): ?>
            <button style="padding:8px 16px;background:#4caf50;color:#fff;border:none;border-radius:6px;font-weight:600;font-size:0.9em;cursor:pointer;transition:background 0.2s;" onmouseover="this.style.background='#45a049'" onmouseout="this.style.background='#4caf50'">
              📥 Descargar Comprobante
            </button>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</main>
<?php include __DIR__ . '/layout/footer.php'; ?>
