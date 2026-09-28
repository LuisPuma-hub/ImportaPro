<?php include __DIR__ . '/layout/header.php'; ?>
<main class="container">
  <div style="display:flex;justify-content:space-between;align-items:center;margin:24px 0;">
    <div>
      <h2 style="margin:0;">Reportes y Estadísticas</h2>
      <p class="muted" style="margin:4px 0 0;">Analiza el rendimiento del negocio</p>
    </div>
    <a href="?action=export_report" class="btn primary" style="display:flex;align-items:center;gap:8px;">
      <span style="font-size:1.2em;">📥</span> Exportar Reporte
    </a>
  </div>
  
  <!-- Stats Cards -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:24px;margin:24px 0;">
    <div class="card" style="padding:24px;background:#e3f2fd;border:1px solid #bbdefb;">
      <div style="display:flex;align-items:center;gap:16px;">
        <div style="width:56px;height:56px;background:#fff;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.8em;">📦</div>
        <div>
          <p style="margin:0;color:#1976d2;font-size:0.9em;">Importaciones del Mes</p>
          <h3 style="margin:4px 0 0;color:#1976d2;font-size:2em;"><?php echo $importsCount; ?></h3>
        </div>
      </div>
    </div>
    
    <div class="card" style="padding:24px;background:#e8f5e9;border:1px solid #c8e6c9;">
      <div style="display:flex;align-items:center;gap:16px;">
        <div style="width:56px;height:56px;background:#fff;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.8em;">💵</div>
        <div>
          <p style="margin:0;color:#2e7d32;font-size:0.9em;">Ingresos del Mes</p>
          <h3 style="margin:4px 0 0;color:#2e7d32;font-size:2em;">$<?php echo number_format($incomeTotal / 100, 0); ?>k</h3>
        </div>
      </div>
    </div>
    
    <div class="card" style="padding:24px;background:#fff3e0;border:1px solid #ffe0b2;">
      <div style="display:flex;align-items:center;gap:16px;">
        <div style="width:56px;height:56px;background:#fff;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.8em;">👥</div>
        <div>
          <p style="margin:0;color:#f57c00;font-size:0.9em;">Nuevos Clientes</p>
          <h3 style="margin:4px 0 0;color:#f57c00;font-size:2em;"><?php echo $newClientsCount; ?></h3>
        </div>
      </div>
    </div>
    
    <div class="card" style="padding:24px;background:#f3e5f5;border:1px solid #e1bee7;">
      <div style="display:flex;align-items:center;gap:16px;">
        <div style="width:56px;height:56px;background:#fff;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.8em;">📈</div>
        <div>
          <p style="margin:0;color:#7b1fa2;font-size:0.9em;">Tasa de Crecimiento</p>
          <h3 style="margin:4px 0 0;color:#7b1fa2;font-size:2em;">+<?php echo number_format($growthRate, 1); ?>%</h3>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Rendimiento Mensual -->
  <div class="card" style="padding:32px;margin:24px 0;">
    <h3 style="margin:0 0 24px;">Rendimiento Mensual (Últimos 6 Meses)</h3>
    <div style="overflow-x:auto;">
      <table class="product-table" style="width:100%;">
        <thead>
          <tr style="background:#f8f9fa;">
            <th style="text-align:left;padding:16px;">MES</th>
            <th style="text-align:center;color:#1976d2;">IMPORTACIONES</th>
            <th style="text-align:right;color:#2e7d32;">INGRESOS</th>
            <th style="text-align:center;color:#f57c00;">NUEVOS CLIENTES</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($monthlyPerformance as $month): ?>
          <tr style="border-bottom:1px solid #eee;">
            <td style="padding:16px;font-weight:500;"><?php echo htmlspecialchars($month['month']); ?></td>
            <td style="text-align:center;color:#1976d2;font-weight:600;"><?php echo $month['imports']; ?></td>
            <td style="text-align:right;color:#2e7d32;font-weight:600;">$<?php echo number_format($month['income'] / 100, 3); ?></td>
            <td style="text-align:center;color:#f57c00;font-weight:600;"><?php echo $month['clients']; ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <!-- Dos columnas: Productos y Clientes -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(400px,1fr));gap:24px;margin:24px 0;">
    <!-- Productos Más Importados -->
    <div class="card" style="padding:32px;">
      <h3 style="margin:0 0 24px;">Productos Más Importados</h3>
      <?php foreach ($topProducts as $index => $prod): ?>
      <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #f0f0f0;">
        <div>
          <p style="margin:0;font-weight:500;color:#111;"><?php echo htmlspecialchars($prod['product_name']); ?></p>
          <p style="margin:4px 0 0;color:#999;font-size:0.9em;"><?php echo $prod['count']; ?> importaciones</p>
        </div>
        <div style="text-align:right;">
          <p style="margin:0;color:#1769ff;font-weight:600;font-size:1.1em;">$<?php echo number_format($prod['count'] * 1850, 0); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($topProducts)): ?>
        <p style="color:#999;text-align:center;padding:24px 0;">No hay datos disponibles</p>
      <?php endif; ?>
    </div>
    
    <!-- Mejores Clientes -->
    <div class="card" style="padding:32px;">
      <h3 style="margin:0 0 24px;">Mejores Clientes</h3>
      <?php foreach ($topClients as $index => $client): ?>
      <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #f0f0f0;">
        <div>
          <p style="margin:0;font-weight:500;color:#111;"><?php echo htmlspecialchars($client['client_name']); ?></p>
          <p style="margin:4px 0 0;color:#999;font-size:0.9em;"><?php echo $client['orders']; ?> pedidos</p>
        </div>
        <div style="text-align:right;">
          <p style="margin:0;color:#2e7d32;font-weight:600;font-size:1.1em;">$<?php echo number_format($client['total_amount'] / 100, 0); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($topClients)): ?>
        <p style="color:#999;text-align:center;padding:24px 0;">No hay datos disponibles</p>
      <?php endif; ?>
    </div>
  </div>
  
</main>

<?php include __DIR__ . '/layout/footer.php'; ?>
