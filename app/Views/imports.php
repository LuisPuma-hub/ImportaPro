<?php if (!empty($_SESSION['import_message'])): ?>
    <div class="alert" style="background:#e0ffe0;color:#155724;padding:8px 16px;margin-bottom:12px;border-radius:4px;">
      <?php echo $_SESSION['import_message']; unset($_SESSION['import_message']); ?>
    </div>
  <?php endif; ?>

<?php include __DIR__ . '/layout/header.php'; ?>
<main class="container">
  <h2 style="margin-top:24px;">Órdenes de Importación</h2>
  <p class="muted">Gestiona y actualiza el estado de las importaciones</p>
  
  <!-- Tabs de filtros -->
  <div style="display:flex;gap:12px;margin:24px 0;flex-wrap:wrap;">
    <a href="?action=imports&status=Todas" class="btn<?php echo ($_GET['status'] ?? 'Todas') === 'Todas' ? ' primary' : ''; ?>" style="<?php echo ($_GET['status'] ?? 'Todas') === 'Todas' ? '' : 'background:#f0f0f0;color:#666;'; ?>">Todas (<?php echo $countAll; ?>)</a>
    <a href="?action=imports&status=Importado" class="btn<?php echo ($_GET['status'] ?? 'Todas') === 'Importado' ? ' primary' : ''; ?>" style="<?php echo ($_GET['status'] ?? 'Todas') === 'Importado' ? '' : 'background:#e8f5e9;color:#2e7d32;'; ?>">Importado (<?php echo $countImported; ?>)</a>
    <a href="?action=imports&status=En%20Tránsito" class="btn<?php echo ($_GET['status'] ?? 'Todas') === 'En Tránsito' ? ' primary' : ''; ?>" style="<?php echo ($_GET['status'] ?? 'Todas') === 'En Tránsito' ? '' : 'background:#fff8e1;color:#f57c00;'; ?>">En Tránsito (<?php echo $countTransit; ?>)</a>
    <a href="?action=imports&status=Aduanas" class="btn<?php echo ($_GET['status'] ?? 'Todas') === 'Aduanas' ? ' primary' : ''; ?>" style="<?php echo ($_GET['status'] ?? 'Todas') === 'Aduanas' ? '' : 'background:#e3f2fd;color:#1976d2;'; ?>">Aduanas (<?php echo $countCustoms; ?>)</a>
    <button id="btnOpenImportModal" type="button" class="btn primary" style="margin-left:auto;">+ Nueva Importación</button>
  </div>

  <!-- Modal para crear/editar importación -->
  <div id="importModalBg" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.18);z-index:1000;align-items:center;justify-content:center;padding:16px;">
    <div id="importModal" style="background:#fff;max-width:600px;width:100%;min-width:300px;border-radius:12px;box-shadow:0 8px 32px rgba(23,105,255,0.18);padding:40px 32px;position:relative;display:flex;flex-direction:column;align-items:center;">
      <h3 style="margin-top:0;">Nueva Importación</h3>
      <form id="importForm">
        <label>Cliente</label>
        <input type="text" name="client_name" required style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <label>Producto</label>
        <input type="text" name="product_name" required style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <label>Cantidad</label>
        <input type="number" name="quantity" value="1" style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <label>Estado</label>
        <select name="status" style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
          <option value="Importado">Importado</option>
          <option value="En Tránsito">En Tránsito</option>
          <option value="Aduanas">Aduanas</option>
        </select>
        
        <label>Progreso (%)</label>
        <input type="number" name="progress" value="0" min="0" max="100" style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <label>Fecha Estimada de Llegada</label>
        <input type="date" name="estimated_arrival" style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <div id="importFormError" class="error" style="display:none;margin-bottom:12px;"></div>
        <div style="display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap;">
          <button type="button" id="btnCancelImport" class="btn" style="flex:1;min-width:100px;background:#fff;color:#1769ff;border:1px solid #1769ff;">Cancelar</button>
          <button type="submit" id="btnSubmitImport" class="btn primary" style="flex:1;min-width:100px;">Crear Importación</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Confirmación eliminación -->
  <div id="deleteImportBg" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.32);z-index:1200;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;box-shadow:0 12px 30px rgba(0,0,0,0.15);padding:24px;max-width:360px;width:90%;text-align:center;">
      <h4 style="margin-top:0;margin-bottom:12px;color:#111;">Eliminar importación</h4>
      <p style="margin:0 0 16px;color:#444;">¿Seguro que deseas eliminar esta importación?</p>
      <div style="display:flex;gap:12px;justify-content:flex-end;">
        <button id="btnDeleteImportNo" class="btn" style="flex:1;background:#f5f5f5;color:#444;border:1px solid #d9d9d9;">Cancelar</button>
        <button id="btnDeleteImportYes" class="btn primary" style="flex:1;">Eliminar</button>
      </div>
    </div>
  </div>

  <!-- Toast mensajes -->
  <div id="importToast" style="position:fixed;bottom:24px;right:24px;background:#1769ff;color:#fff;padding:12px 16px;border-radius:8px;box-shadow:0 10px 24px rgba(23,105,255,0.25);opacity:0;pointer-events:none;transition:opacity 0.2s ease-out;z-index:1300;"></div>

  <!-- Cards de importaciones -->
  <div style="display:grid;grid-template-columns:1fr;gap:24px;margin-top:24px;">
    <?php foreach ($imports as $imp): 
      $statusColor = $imp['status'] === 'Importado' ? '#2e7d32' : ($imp['status'] === 'En Tránsito' ? '#f57c00' : '#1976d2');
      $statusBg = $imp['status'] === 'Importado' ? '#e8f5e9' : ($imp['status'] === 'En Tránsito' ? '#fff8e1' : '#e3f2fd');
    ?>
    <div class="card" style="padding:24px;background:#fff;border:1px solid #e0e0e0;">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
        <div>
          <h4 style="margin:0;color:#111;"><?php echo htmlspecialchars($imp['id']); ?></h4>
          <span style="background:<?php echo $statusBg; ?>;color:<?php echo $statusColor; ?>;padding:4px 8px;border-radius:4px;font-size:0.9em;font-weight:500;"><?php echo htmlspecialchars($imp['status']); ?></span>
        </div>
        <div style="display:flex;gap:8px;">
          <a href="#" class="action-icon edit" onclick="openEditImportModal('<?php echo $imp['id']; ?>'); return false;" style="font-size:1.2em;">✏️</a>
          <a href="#" class="action-icon delete" onclick="openDeleteImportConfirm('<?php echo $imp['id']; ?>'); return false;" style="font-size:1.2em;">🗑️</a>
        </div>
      </div>
      
      <div style="margin-bottom:12px;">
        <p style="margin:4px 0;color:#666;"><strong>Cliente:</strong> <?php echo htmlspecialchars($imp['client_name']); ?></p>
        <p style="margin:4px 0;color:#666;"><strong><?php echo htmlspecialchars($imp['product_name']); ?></strong> x<?php echo $imp['quantity']; ?></p>
        <p style="margin:4px 0;color:#999;font-size:0.9em;">Llegada estimada: <?php echo htmlspecialchars($imp['estimated_arrival'] ?? 'No definida'); ?></p>
      </div>
      
      <div style="margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
          <span style="font-size:0.9em;color:#666;">Progreso</span>
          <span style="font-weight:600;color:#1769ff;"><?php echo $imp['progress']; ?>%</span>
        </div>
        <div style="background:#f0f0f0;height:8px;border-radius:4px;overflow:hidden;">
          <div style="background:#1769ff;height:100%;width:<?php echo $imp['progress']; ?>%;transition:width 0.3s ease;"></div>
        </div>
      </div>
      
      <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <button onclick="openEditImportModal('<?php echo $imp['id']; ?>')" class="btn primary" style="flex:1;min-width:120px;">Ver Detalles</button>
        <button onclick="updateImportProgress('<?php echo $imp['id']; ?>')" class="btn" style="flex:1;min-width:120px;background:#f0f0f0;color:#666;border:1px solid #d9d9d9;">Actualizar</button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <?php if (empty($imports)): ?>
    <div style="text-align:center;padding:48px 24px;color:#999;">
      <p style="font-size:1.1em;">No hay importaciones en esta categoría</p>
    </div>
  <?php endif; ?>

</main>

<script>
  const importModalBg = document.getElementById('importModalBg');
  const importForm = document.getElementById('importForm');
  const importToast = document.getElementById('importToast');
  const deleteImportBg = document.getElementById('deleteImportBg');
  const btnDeleteYes = document.getElementById('btnDeleteImportYes');
  const btnDeleteNo = document.getElementById('btnDeleteImportNo');
  const importError = document.getElementById('importFormError');
  let pendingDeleteId = null;

  function showToast(message, isError = false) {
    importToast.textContent = message;
    importToast.style.background = isError ? '#e53935' : '#1769ff';
    importToast.style.opacity = '1';
    setTimeout(() => { importToast.style.opacity = '0'; }, 2000);
  }

  function openDeleteImportConfirm(id) {
    pendingDeleteId = id;
    deleteImportBg.style.display = 'flex';
  }

  function closeDeleteConfirm() {
    deleteImportBg.style.display = 'none';
    pendingDeleteId = null;
  }

  btnDeleteNo.addEventListener('click', closeDeleteConfirm);

  btnDeleteYes.addEventListener('click', async function() {
    if (!pendingDeleteId) return;
    try {
      const res = await fetch(`?action=delete_import&id=${pendingDeleteId}`);
      closeDeleteConfirm();
      if (res.ok) {
        showToast('Importación eliminada correctamente');
        setTimeout(() => window.location.reload(), 2000);
      } else {
        showToast('Error al eliminar', true);
      }
    } catch (err) {
      closeDeleteConfirm();
      showToast('Error de red', true);
    }
  });

  function openEditImportModal(id) {
    fetch('?action=get_import&id=' + id)
      .then(res => res.json())
      .then(data => {
        if (data && data.id) {
          document.querySelector('#importModal h3').textContent = 'Editar Importación';
          document.getElementById('btnSubmitImport').textContent = 'Guardar Cambios';
          importForm.querySelector('[name=client_name]').value = data.client_name || '';
          importForm.querySelector('[name=product_name]').value = data.product_name || '';
          importForm.querySelector('[name=quantity]').value = data.quantity || 0;
          importForm.querySelector('[name=status]').value = data.status || 'Importado';
          importForm.querySelector('[name=progress]').value = data.progress || 0;
          importForm.querySelector('[name=estimated_arrival]').value = data.estimated_arrival || '';
          importForm.setAttribute('data-edit-id', id);
          importError.style.display = 'none';
          importModalBg.style.display = 'flex';
        }
      });
  }

  function updateImportProgress(id) {
    openEditImportModal(id);
  }

  document.getElementById('btnOpenImportModal').onclick = function() {
    document.querySelector('#importModal h3').textContent = 'Nueva Importación';
    document.getElementById('btnSubmitImport').textContent = 'Crear Importación';
    importForm.reset();
    importForm.removeAttribute('data-edit-id');
    importForm.querySelector('[name=estimated_arrival]').valueAsDate = new Date();
    importError.style.display = 'none';
    importModalBg.style.display = 'flex';
  };

  document.getElementById('btnCancelImport').onclick = function() {
    importModalBg.style.display = 'none';
  };

  importForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    importError.style.display = 'none';
    const formData = new FormData(importForm);
    const editId = importForm.getAttribute('data-edit-id');
    const url = editId ? `?action=update_import&id=${editId}` : '?action=store_import';
    try {
      const res = await fetch(url, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const data = await res.json();
      if (data.success) {
        importModalBg.style.display = 'none';
        window.location.reload();
      } else {
        importError.textContent = data.error || 'Error al guardar importación';
        importError.style.display = 'block';
      }
    } catch (err) {
      importError.textContent = 'Error de red o servidor';
      importError.style.display = 'block';
    }
  });

  deleteImportBg.addEventListener('click', function(e) {
    if (e.target === deleteImportBg) closeDeleteConfirm();
  });
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
