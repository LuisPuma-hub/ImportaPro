<?php if (!empty($_SESSION['payment_message'])): ?>
    <div class="alert" style="background:#e0ffe0;color:#155724;padding:8px 16px;margin-bottom:12px;border-radius:4px;">
      <?php echo $_SESSION['payment_message']; unset($_SESSION['payment_message']); ?>
    </div>
  <?php endif; ?>

<?php include __DIR__ . '/layout/header.php'; ?>
<main class="container">
  <h2 style="margin-top:24px;">Gestión de Pagos</h2>
  <p class="muted">Verifica y administra los pagos de clientes</p>
  
  <!-- Stats Cards -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;margin:24px 0;">
    <div class="card" style="padding:24px;background:#fff8e1;border:1px solid #ffecb3;">
      <div style="display:flex;align-items:center;gap:16px;">
        <div style="width:48px;height:48px;background:#fff;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5em;">💵</div>
        <div>
          <p style="margin:0;color:#f57c00;font-size:0.9em;">Pendientes de Verificar</p>
          <h3 style="margin:4px 0 0;color:#f57c00;">$<?php echo number_format($totalPending / 100, 2); ?></h3>
        </div>
      </div>
    </div>
    
    <div class="card" style="padding:24px;background:#e8f5e9;border:1px solid #c8e6c9;">
      <div style="display:flex;align-items:center;gap:16px;">
        <div style="width:48px;height:48px;background:#fff;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5em;">✅</div>
        <div>
          <p style="margin:0;color:#2e7d32;font-size:0.9em;">Verificados</p>
          <h3 style="margin:4px 0 0;color:#2e7d32;">$<?php echo number_format($totalVerified / 100, 2); ?></h3>
        </div>
      </div>
    </div>
    
    <div class="card" style="padding:24px;background:#e3f2fd;border:1px solid #bbdefb;">
      <div style="display:flex;align-items:center;gap:16px;">
        <div style="width:48px;height:48px;background:#fff;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5em;">💰</div>
        <div>
          <p style="margin:0;color:#1976d2;font-size:0.9em;">Total del Mes</p>
          <h3 style="margin:4px 0 0;color:#1976d2;">$<?php echo number_format($totalMonth / 100, 2); ?></h3>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Tabs de filtros -->
  <div style="display:flex;gap:12px;margin:24px 0;flex-wrap:wrap;">
    <a href="?action=payments&status=Todos" class="btn<?php echo ($_GET['status'] ?? 'Todos') === 'Todos' ? ' primary' : ''; ?>" style="<?php echo ($_GET['status'] ?? 'Todos') === 'Todos' ? '' : 'background:#f0f0f0;color:#666;'; ?>">Todos (<?php echo $countAll; ?>)</a>
    <a href="?action=payments&status=Pendiente" class="btn<?php echo ($_GET['status'] ?? 'Todos') === 'Pendiente' ? ' primary' : ''; ?>" style="<?php echo ($_GET['status'] ?? 'Todos') === 'Pendiente' ? '' : 'background:#fff8e1;color:#f57c00;'; ?>">Pendientes (<?php echo $countPending; ?>)</a>
    <a href="?action=payments&status=Verificado" class="btn<?php echo ($_GET['status'] ?? 'Todos') === 'Verificado' ? ' primary' : ''; ?>" style="<?php echo ($_GET['status'] ?? 'Todos') === 'Verificado' ? '' : 'background:#e8f5e9;color:#2e7d32;'; ?>">Verificados (<?php echo $countVerified; ?>)</a>
  </div>

  <!-- Modal para crear/editar pago -->
  <div id="paymentModalBg" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.18);z-index:1000;align-items:center;justify-content:center;padding:16px;">
    <div id="paymentModal" style="background:#fff;max-width:600px;width:100%;min-width:300px;border-radius:12px;box-shadow:0 8px 32px rgba(23,105,255,0.18);padding:40px 32px;position:relative;display:flex;flex-direction:column;align-items:center;">
      <h3 style="margin-top:0;">Nuevo Pago</h3>
      <form id="paymentForm">
        <label>Cliente</label>
        <input type="text" name="client_name" required style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <label>Orden</label>
        <input type="text" name="order_id" required placeholder="ORD-2024-045" style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <label>Monto (centavos)</label>
        <input type="number" name="amount_cents" value="0" style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <label>Método</label>
        <select name="method" style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
          <option value="Transferencia">Transferencia</option>
          <option value="Tarjeta">Tarjeta</option>
          <option value="PayPal">PayPal</option>
          <option value="Efectivo">Efectivo</option>
        </select>
        
        <label>Estado</label>
        <select name="status" style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
          <option value="Pendiente">Pendiente</option>
          <option value="Verificado">Verificado</option>
          <option value="Rechazado">Rechazado</option>
        </select>
        
        <label>Fecha de Pago</label>
        <input type="date" name="payment_date" style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <div id="paymentFormError" class="error" style="display:none;margin-bottom:12px;"></div>
        <div style="display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap;">
          <button type="button" id="btnCancelPayment" class="btn" style="flex:1;min-width:100px;background:#fff;color:#1769ff;border:1px solid #1769ff;">Cancelar</button>
          <button type="submit" id="btnSubmitPayment" class="btn primary" style="flex:1;min-width:100px;">Crear Pago</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Confirmación eliminación -->
  <div id="deletePaymentBg" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.32);z-index:1200;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;box-shadow:0 12px 30px rgba(0,0,0,0.15);padding:24px;max-width:360px;width:90%;text-align:center;">
      <h4 style="margin-top:0;margin-bottom:12px;color:#111;">Eliminar pago</h4>
      <p style="margin:0 0 16px;color:#444;">¿Seguro que deseas eliminar este pago?</p>
      <div style="display:flex;gap:12px;justify-content:flex-end;">
        <button id="btnDeletePaymentNo" class="btn" style="flex:1;background:#f5f5f5;color:#444;border:1px solid #d9d9d9;">Cancelar</button>
        <button id="btnDeletePaymentYes" class="btn primary" style="flex:1;">Eliminar</button>
      </div>
    </div>
  </div>

  <!-- Toast mensajes -->
  <div id="paymentToast" style="position:fixed;bottom:24px;right:24px;background:#1769ff;color:#fff;padding:12px 16px;border-radius:8px;box-shadow:0 10px 24px rgba(23,105,255,0.25);opacity:0;pointer-events:none;transition:opacity 0.2s ease-out;z-index:1300;"></div>

  <!-- Lista de pagos -->
  <div style="display:grid;grid-template-columns:1fr;gap:16px;margin-top:24px;">
    <?php foreach ($payments as $pay): 
      $statusColor = $pay['status'] === 'Pendiente' ? '#f57c00' : ($pay['status'] === 'Verificado' ? '#2e7d32' : '#e53935');
      $statusBg = $pay['status'] === 'Pendiente' ? '#fff8e1' : ($pay['status'] === 'Verificado' ? '#e8f5e9' : '#ffebee');
    ?>
    <div class="card" style="padding:24px;background:#fff;border:1px solid #e0e0e0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
      <div style="flex:1;min-width:200px;">
        <h4 style="margin:0 0 8px;color:#111;"><?php echo htmlspecialchars($pay['id']); ?></h4>
        <span style="background:<?php echo $statusBg; ?>;color:<?php echo $statusColor; ?>;padding:4px 8px;border-radius:4px;font-size:0.85em;font-weight:500;"><?php echo htmlspecialchars($pay['status']); ?></span>
        <p style="margin:12px 0 4px;color:#666;"><strong>Cliente:</strong> <?php echo htmlspecialchars($pay['client_name']); ?></p>
        <p style="margin:4px 0;color:#666;">Orden: <?php echo htmlspecialchars($pay['order_id']); ?></p>
        <p style="margin:4px 0;color:#999;font-size:0.9em;">Método: <?php echo htmlspecialchars($pay['method']); ?> • <?php echo htmlspecialchars($pay['payment_date'] ?? 'Sin fecha'); ?></p>
      </div>
      
      <div style="text-align:right;">
        <h3 style="margin:0 0 12px;color:#1769ff;">$<?php echo number_format($pay['amount_cents'] / 100, 2); ?></h3>
        <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end;">
          <?php if ($pay['status'] === 'Pendiente'): ?>
            <button onclick="verifyPayment('<?php echo $pay['id']; ?>')" class="btn" style="background:#2e7d32;color:#fff;padding:6px 12px;font-size:0.9em;">✓ Verificar</button>
            <button onclick="rejectPayment('<?php echo $pay['id']; ?>')" class="btn" style="background:#e53935;color:#fff;padding:6px 12px;font-size:0.9em;">✕ Rechazar</button>
          <?php endif; ?>
          <button onclick="viewPaymentProof('<?php echo $pay['id']; ?>')" class="btn" style="background:#f0f0f0;color:#666;padding:6px 12px;font-size:0.9em;">👁️ Ver Comprobante</button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <?php if (empty($payments)): ?>
    <div style="text-align:center;padding:48px 24px;color:#999;">
      <p style="font-size:1.1em;">No hay pagos en esta categoría</p>
    </div>
  <?php endif; ?>

</main>

<script>
  const paymentModalBg = document.getElementById('paymentModalBg');
  const paymentForm = document.getElementById('paymentForm');
  const paymentToast = document.getElementById('paymentToast');
  const deletePaymentBg = document.getElementById('deletePaymentBg');
  const btnDeleteYes = document.getElementById('btnDeletePaymentYes');
  const btnDeleteNo = document.getElementById('btnDeletePaymentNo');
  const paymentError = document.getElementById('paymentFormError');
  let pendingDeleteId = null;

  function showToast(message, isError = false) {
    paymentToast.textContent = message;
    paymentToast.style.background = isError ? '#e53935' : '#1769ff';
    paymentToast.style.opacity = '1';
    setTimeout(() => { paymentToast.style.opacity = '0'; }, 2000);
  }

  function openDeletePaymentConfirm(id) {
    pendingDeleteId = id;
    deletePaymentBg.style.display = 'flex';
  }

  function closeDeleteConfirm() {
    deletePaymentBg.style.display = 'none';
    pendingDeleteId = null;
  }

  btnDeleteNo.addEventListener('click', closeDeleteConfirm);

  btnDeleteYes.addEventListener('click', async function() {
    if (!pendingDeleteId) return;
    try {
      const res = await fetch(`?action=delete_payment&id=${pendingDeleteId}`);
      closeDeleteConfirm();
      if (res.ok) {
        showToast('Pago eliminado correctamente');
        setTimeout(() => window.location.reload(), 2000);
      } else {
        showToast('Error al eliminar', true);
      }
    } catch (err) {
      closeDeleteConfirm();
      showToast('Error de red', true);
    }
  });

  function verifyPayment(id) {
    if (confirm('¿Verificar este pago?')) {
      fetch(`?action=verify_payment&id=${id}`)
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            showToast('Pago verificado correctamente');
            setTimeout(() => window.location.reload(), 2000);
          }
        });
    }
  }

  function rejectPayment(id) {
    if (confirm('¿Rechazar este pago?')) {
      fetch(`?action=reject_payment&id=${id}`)
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            showToast('Pago rechazado');
            setTimeout(() => window.location.reload(), 2000);
          }
        });
    }
  }

  function viewPaymentProof(id) {
    showToast('Función de comprobante no implementada', true);
  }

  document.getElementById('btnCancelPayment').onclick = function() {
    paymentModalBg.style.display = 'none';
  };

  deletePaymentBg.addEventListener('click', function(e) {
    if (e.target === deletePaymentBg) closeDeleteConfirm();
  });
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
