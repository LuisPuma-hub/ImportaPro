<?php if (!empty($_SESSION['quote_message'])): ?>
    <div class="alert" style="background:#e0ffe0;color:#155724;padding:8px 16px;margin-bottom:12px;border-radius:4px;">
      <?php echo $_SESSION['quote_message']; unset($_SESSION['quote_message']); ?>
    </div>
  <?php endif; ?>

<?php include __DIR__ . '/layout/header.php'; ?>
<main class="container">
  <h2 style="margin-top:24px;">Gestión de Cotizaciones</h2>
  <p class="muted">Responde a solicitudes de cotización de clientes</p>
  
  <!-- Tabs de filtros -->
  <div style="display:flex;gap:12px;margin:24px 0;flex-wrap:wrap;">
    <a href="?action=quotes&status=Todas" class="btn<?php echo ($_GET['status'] ?? 'Todas') === 'Todas' ? ' primary' : ''; ?>" style="<?php echo ($_GET['status'] ?? 'Todas') === 'Todas' ? '' : 'background:#f0f0f0;color:#666;'; ?>">Todas (<?php echo $countAll; ?>)</a>
    <a href="?action=quotes&status=Pendiente" class="btn<?php echo ($_GET['status'] ?? 'Todas') === 'Pendiente' ? ' primary' : ''; ?>" style="<?php echo ($_GET['status'] ?? 'Todas') === 'Pendiente' ? '' : 'background:#fff8e1;color:#f57c00;'; ?>">Pendientes (<?php echo $countPending; ?>)</a>
    <a href="?action=quotes&status=Cotizado" class="btn<?php echo ($_GET['status'] ?? 'Todas') === 'Cotizado' ? ' primary' : ''; ?>" style="<?php echo ($_GET['status'] ?? 'Todas') === 'Cotizado' ? '' : 'background:#e8f5e9;color:#2e7d32;'; ?>">Cotizadas (<?php echo $countQuoted; ?>)</a>
    <a href="?action=quotes&status=Aprobado" class="btn<?php echo ($_GET['status'] ?? 'Todas') === 'Aprobado' ? ' primary' : ''; ?>" style="<?php echo ($_GET['status'] ?? 'Todas') === 'Aprobado' ? '' : 'background:#e3f2fd;color:#1976d2;'; ?>">Aprobadas (<?php echo $countApproved; ?>)</a>
    <button id="btnOpenQuoteModal" type="button" class="btn primary" style="margin-left:auto;">+ Nueva Cotización</button>
  </div>

  <!-- Modal para crear/editar cotización -->
  <div id="quoteModalBg" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.18);z-index:1000;align-items:center;justify-content:center;padding:16px;">
    <div id="quoteModal" style="background:#fff;max-width:600px;width:100%;min-width:300px;border-radius:12px;box-shadow:0 8px 32px rgba(23,105,255,0.18);padding:40px 32px;position:relative;display:flex;flex-direction:column;align-items:center;">
      <h3 style="margin-top:0;">Nueva Cotización</h3>
      <form id="quoteForm">
        <label>Cliente</label>
        <input type="text" name="client_name" required style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <label>Producto</label>
        <input type="text" name="product_name" required style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <label>Cantidad</label>
        <input type="number" name="quantity" value="1" style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <label>Estado</label>
        <select name="status" style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
          <option value="Pendiente">Pendiente</option>
          <option value="Cotizado">Cotizado</option>
          <option value="Aprobado">Aprobado</option>
        </select>
        
        <label>Fecha Solicitada</label>
        <input type="date" name="requested_date" style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <div id="quoteFormError" class="error" style="display:none;margin-bottom:12px;"></div>
        <div style="display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap;">
          <button type="button" id="btnCancelQuote" class="btn" style="flex:1;min-width:100px;background:#fff;color:#1769ff;border:1px solid #1769ff;">Cancelar</button>
          <button type="submit" id="btnSubmitQuote" class="btn primary" style="flex:1;min-width:100px;">Crear Cotización</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Confirmación eliminación -->
  <div id="deleteQuoteBg" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.32);z-index:1200;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;box-shadow:0 12px 30px rgba(0,0,0,0.15);padding:24px;max-width:360px;width:90%;text-align:center;">
      <h4 style="margin-top:0;margin-bottom:12px;color:#111;">Eliminar cotización</h4>
      <p style="margin:0 0 16px;color:#444;">¿Seguro que deseas eliminar esta cotización?</p>
      <div style="display:flex;gap:12px;justify-content:flex-end;">
        <button id="btnDeleteQuoteNo" class="btn" style="flex:1;background:#f5f5f5;color:#444;border:1px solid #d9d9d9;">Cancelar</button>
        <button id="btnDeleteQuoteYes" class="btn primary" style="flex:1;">Eliminar</button>
      </div>
    </div>
  </div>

  <!-- Toast mensajes -->
  <div id="quoteToast" style="position:fixed;bottom:24px;right:24px;background:#1769ff;color:#fff;padding:12px 16px;border-radius:8px;box-shadow:0 10px 24px rgba(23,105,255,0.25);opacity:0;pointer-events:none;transition:opacity 0.2s ease-out;z-index:1300;"></div>

  <!-- Tabla de cotizaciones -->
  <div class="card" style="width:100%;margin:0 auto;">
    <div style="overflow-x:auto;">
      <table class="product-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>CLIENTE</th>
            <th>PRODUCTO</th>
            <th>CANTIDAD</th>
            <th>ESTADO</th>
            <th>SOLICITADO</th>
            <th>ACCIONES</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($quotes as $q): 
            $statusColor = match($q['status']) {
              'Pendiente' => '#f57c00',
              'Cotizado' => '#2e7d32',
              'Aprobado' => '#1976d2',
              default => '#999'
            };
            $statusBg = match($q['status']) {
              'Pendiente' => '#fff8e1',
              'Cotizado' => '#e8f5e9',
              'Aprobado' => '#e3f2fd',
              default => '#f5f5f5'
            };
          ?>
          <tr>
            <td><strong><?php echo htmlspecialchars($q['id']); ?></strong></td>
            <td><?php echo htmlspecialchars($q['client_name']); ?></td>
            <td><?php echo htmlspecialchars($q['product_name']); ?></td>
            <td><?php echo htmlspecialchars($q['quantity']); ?> unidades</td>
            <td><span style="background:<?php echo $statusBg; ?>;color:<?php echo $statusColor; ?>;padding:4px 8px;border-radius:4px;font-size:0.9em;font-weight:500;"><?php echo htmlspecialchars($q['status']); ?></span></td>
            <td><?php echo htmlspecialchars($q['requested_date']); ?></td>
            <td>
              <a href="#" class="action-icon edit" onclick="openEditQuoteModal(<?php echo $q['id']; ?>); return false;" style="margin-right:8px;">✏️</a>
              <a href="#" class="action-icon delete" onclick="openDeleteQuoteConfirm(<?php echo $q['id']; ?>); return false;">🗑️</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php if (empty($quotes)): ?>
    <div style="text-align:center;padding:48px 24px;color:#999;">
      <p style="font-size:1.1em;">No hay cotizaciones en esta categoría</p>
    </div>
  <?php endif; ?>

</main>

<script>
  const quoteModalBg = document.getElementById('quoteModalBg');
  const quoteForm = document.getElementById('quoteForm');
  const quoteToast = document.getElementById('quoteToast');
  const deleteQuoteBg = document.getElementById('deleteQuoteBg');
  const btnDeleteYes = document.getElementById('btnDeleteQuoteYes');
  const btnDeleteNo = document.getElementById('btnDeleteQuoteNo');
  const quoteError = document.getElementById('quoteFormError');
  let pendingDeleteId = null;

  function showToast(message, isError = false) {
    quoteToast.textContent = message;
    quoteToast.style.background = isError ? '#e53935' : '#1769ff';
    quoteToast.style.opacity = '1';
    setTimeout(() => { quoteToast.style.opacity = '0'; }, 2000);
  }

  function openDeleteQuoteConfirm(id) {
    pendingDeleteId = id;
    deleteQuoteBg.style.display = 'flex';
  }

  function closeDeleteConfirm() {
    deleteQuoteBg.style.display = 'none';
    pendingDeleteId = null;
  }

  btnDeleteNo.addEventListener('click', closeDeleteConfirm);

  btnDeleteYes.addEventListener('click', async function() {
    if (!pendingDeleteId) return;
    try {
      const res = await fetch(`?action=delete_quote&id=${pendingDeleteId}`);
      closeDeleteConfirm();
      if (res.ok) {
        showToast('Cotización eliminada correctamente');
        setTimeout(() => window.location.reload(), 2000);
      } else {
        showToast('Error al eliminar', true);
      }
    } catch (err) {
      closeDeleteConfirm();
      showToast('Error de red', true);
    }
  });

  function openEditQuoteModal(id) {
    fetch('?action=get_quote&id=' + id)
      .then(res => res.json())
      .then(data => {
        if (data && data.id) {
          document.querySelector('#quoteModal h3').textContent = 'Editar Cotización';
          document.getElementById('btnSubmitQuote').textContent = 'Guardar Cambios';
          quoteForm.querySelector('[name=client_name]').value = data.client_name || '';
          quoteForm.querySelector('[name=product_name]').value = data.product_name || '';
          quoteForm.querySelector('[name=quantity]').value = data.quantity || 0;
          quoteForm.querySelector('[name=status]').value = data.status || 'Pendiente';
          quoteForm.querySelector('[name=requested_date]').value = data.requested_date || '';
          quoteForm.setAttribute('data-edit-id', id);
          quoteError.style.display = 'none';
          quoteModalBg.style.display = 'flex';
        }
      });
  }

  document.getElementById('btnOpenQuoteModal').onclick = function() {
    document.querySelector('#quoteModal h3').textContent = 'Nueva Cotización';
    document.getElementById('btnSubmitQuote').textContent = 'Crear Cotización';
    quoteForm.reset();
    quoteForm.removeAttribute('data-edit-id');
    quoteForm.querySelector('[name=requested_date]').valueAsDate = new Date();
    quoteError.style.display = 'none';
    quoteModalBg.style.display = 'flex';
  };

  document.getElementById('btnCancelQuote').onclick = function() {
    quoteModalBg.style.display = 'none';
  };

  quoteForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    quoteError.style.display = 'none';
    const formData = new FormData(quoteForm);
    const editId = quoteForm.getAttribute('data-edit-id');
    const url = editId ? `?action=update_quote&id=${editId}` : '?action=store_quote';
    try {
      const res = await fetch(url, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const data = await res.json();
      if (data.success) {
        quoteModalBg.style.display = 'none';
        window.location.reload();
      } else {
        quoteError.textContent = data.error || 'Error al guardar cotización';
        quoteError.style.display = 'block';
      }
    } catch (err) {
      quoteError.textContent = 'Error de red o servidor';
      quoteError.style.display = 'block';
    }
  });

  deleteQuoteBg.addEventListener('click', function(e) {
    if (e.target === deleteQuoteBg) closeDeleteConfirm();
  });
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
