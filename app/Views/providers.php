<!-- Gestión de Proveedores -->
<?php if (!empty($_SESSION['provider_message'])): ?>
    <div class="alert" style="background:#e0ffe0;color:#155724;padding:8px 16px;margin-bottom:12px;border-radius:4px;">
      <?php echo $_SESSION['provider_message']; unset($_SESSION['provider_message']); ?>
    </div>
  <?php endif; ?>

<?php include __DIR__ . '/layout/header.php'; ?>
<main class="container">
  <h2 style="margin-top:24px;">Proveedores Extranjeros</h2>
  <p class="muted">Gestiona tus proveedores internacionales</p>
  
  <div class="card" style="margin-bottom:24px;">
    <form method="get" style="display:flex;gap:16px;align-items:center;">
      <input type="text" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" placeholder="Buscar proveedores..." style="flex:1;padding:10px 16px;border-radius:8px;border:1px solid #e3e8ee;">
      <button class="btn primary" type="submit">Buscar</button>
      <button id="btnOpenProviderModal" type="button" class="btn primary" style="margin-left:auto;">+ Nuevo Proveedor</button>
    </form>
  </div>

  <!-- Modal para crear/editar proveedor -->
  <div id="providerModalBg" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.18);z-index:1000;align-items:center;justify-content:center;padding:16px;">
    <div id="providerModal" style="background:#fff;max-width:600px;width:100%;min-width:300px;min-height:420px;border-radius:12px;box-shadow:0 8px 32px rgba(23,105,255,0.18);padding:40px 32px;position:relative;display:flex;flex-direction:column;align-items:center;">
      <h3 style="margin-top:0;">Nuevo Proveedor</h3>
      <form id="providerForm">
        <label>Nombre del Proveedor</label>
        <input type="text" name="name" required style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <label>País</label>
        <input type="text" name="country" style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <label>Contacto</label>
        <input type="email" name="contact" style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
        
        <div style="display:flex;gap:12px;flex-wrap:wrap;">
          <div style="flex:1;min-width:150px;">
            <label>Cantidad de Productos</label>
            <input type="number" name="products_count" value="0" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
          </div>
          <div style="flex:1;min-width:150px;">
            <label>Calificación</label>
            <input type="number" name="rating" step="0.1" min="0" max="5" value="0" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
          </div>
        </div>
        
        <div id="providerFormError" class="error" style="display:none;margin-bottom:12px;"></div>
        <div style="display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap;">
          <button type="button" id="btnCancelProvider" class="btn" style="flex:1;min-width:100px;background:#fff;color:#1769ff;border:1px solid #1769ff;">Cancelar</button>
          <button type="submit" id="btnSubmitProvider" class="btn primary" style="flex:1;min-width:100px;">Crear Proveedor</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Confirmación eliminación -->
  <div id="deleteProviderBg" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.32);z-index:1200;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;box-shadow:0 12px 30px rgba(0,0,0,0.15);padding:24px;max-width:360px;width:90%;text-align:center;">
      <h4 style="margin-top:0;margin-bottom:12px;color:#111;">Eliminar proveedor</h4>
      <p style="margin:0 0 16px;color:#444;">¿Seguro que deseas eliminar este proveedor?</p>
      <div style="display:flex;gap:12px;justify-content:flex-end;">
        <button id="btnDeleteProviderNo" class="btn" style="flex:1;background:#f5f5f5;color:#444;border:1px solid #d9d9d9;">Cancelar</button>
        <button id="btnDeleteProviderYes" class="btn primary" style="flex:1;">Eliminar</button>
      </div>
    </div>
  </div>

  <!-- Toast mensajes -->
  <div id="providerToast" style="position:fixed;bottom:24px;right:24px;background:#1769ff;color:#fff;padding:12px 16px;border-radius:8px;box-shadow:0 10px 24px rgba(23,105,255,0.25);opacity:0;pointer-events:none;transition:opacity 0.2s ease-out;z-index:1300;"></div>

  <!-- Grid de proveedores -->
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:24px;margin-top:24px;">
    <?php foreach ($providers as $p): ?>
      <div style="background:#fff;border-radius:12px;padding:24px;box-shadow:0 2px 12px rgba(23,105,255,0.08);border:1px solid #e8ecf1;transition:box-shadow 0.3s;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
          <div style="width:48px;height:48px;background:linear-gradient(135deg,#1769ff,#0f3fae);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;">🌍</div>
          <div style="flex:1;">
            <h4 style="margin:0;font-size:1.1em;color:#111;"><?php echo htmlspecialchars($p['name']); ?></h4>
            <p style="margin:4px 0 0;color:#666;font-size:0.9em;"><?php echo htmlspecialchars($p['country'] ?? '-'); ?></p>
          </div>
        </div>
        
        <p style="margin:12px 0;color:#555;font-size:0.95em;">
          <strong>Contacto:</strong> <?php echo htmlspecialchars($p['contact'] ?? '-'); ?>
        </p>
        
        <div style="display:flex;gap:16px;margin:16px 0;padding:12px 0;border-top:1px solid #f0f0f0;border-bottom:1px solid #f0f0f0;">
          <div>
            <p style="margin:0;color:#999;font-size:0.85em;">Productos</p>
            <p style="margin:4px 0 0;font-size:1.2em;font-weight:600;color:#1769ff;"><?php echo htmlspecialchars($p['products_count'] ?? 0); ?></p>
          </div>
          <div>
            <p style="margin:0;color:#999;font-size:0.85em;">Calificación</p>
            <p style="margin:4px 0 0;font-size:1.2em;font-weight:600;">⭐ <?php echo htmlspecialchars($p['rating'] ?? 0); ?></p>
          </div>
        </div>
        
        <div style="display:flex;gap:12px;">
          <button class="btn primary" onclick="openEditProviderModal(<?php echo $p['id']; ?>)" style="flex:1;">✏️ Editar</button>
          <button class="btn" style="flex:1;background:#fff;color:#d32f2f;border:1px solid #d32f2f;" onclick="openDeleteProviderConfirm(<?php echo $p['id']; ?>)">🗑️ Eliminar</button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if (empty($providers)): ?>
    <div style="text-align:center;padding:48px 24px;color:#999;">
      <p style="font-size:1.1em;">No hay proveedores registrados</p>
    </div>
  <?php endif; ?>

</main>

<script>
  const providerModalBg = document.getElementById('providerModalBg');
  const providerForm = document.getElementById('providerForm');
  const providerToast = document.getElementById('providerToast');
  const deleteProviderBg = document.getElementById('deleteProviderBg');
  const btnDeleteYes = document.getElementById('btnDeleteProviderYes');
  const btnDeleteNo = document.getElementById('btnDeleteProviderNo');
  const providerError = document.getElementById('providerFormError');
  let pendingDeleteId = null;

  function showToast(message, isError = false) {
    providerToast.textContent = message;
    providerToast.style.background = isError ? '#e53935' : '#1769ff';
    providerToast.style.opacity = '1';
    setTimeout(() => { providerToast.style.opacity = '0'; }, 2000);
  }

  function openDeleteProviderConfirm(id) {
    pendingDeleteId = id;
    deleteProviderBg.style.display = 'flex';
  }

  function closeDeleteConfirm() {
    deleteProviderBg.style.display = 'none';
    pendingDeleteId = null;
  }

  btnDeleteNo.addEventListener('click', closeDeleteConfirm);

  btnDeleteYes.addEventListener('click', async function() {
    if (!pendingDeleteId) return;
    try {
      const res = await fetch(`?action=delete_provider&id=${pendingDeleteId}`);
      closeDeleteConfirm();
      if (res.ok) {
        showToast('Proveedor eliminado correctamente');
        setTimeout(() => window.location.reload(), 2000);
      } else {
        showToast('Error al eliminar', true);
      }
    } catch (err) {
      closeDeleteConfirm();
      showToast('Error de red', true);
    }
  });

  function openEditProviderModal(id) {
    fetch('?action=get_provider&id=' + id)
      .then(res => res.json())
      .then(data => {
        if (data && data.id) {
          document.querySelector('#providerModal h3').textContent = 'Editar Proveedor';
          document.getElementById('btnSubmitProvider').textContent = 'Guardar Cambios';
          providerForm.querySelector('[name=name]').value = data.name || '';
          providerForm.querySelector('[name=country]').value = data.country || '';
          providerForm.querySelector('[name=contact]').value = data.contact || '';
          providerForm.querySelector('[name=products_count]').value = data.products_count || 0;
          providerForm.querySelector('[name=rating]').value = data.rating || 0;
          providerForm.setAttribute('data-edit-id', id);
          providerError.style.display = 'none';
          providerModalBg.style.display = 'flex';
        }
      });
  }

  document.getElementById('btnOpenProviderModal').onclick = function() {
    document.querySelector('#providerModal h3').textContent = 'Nuevo Proveedor';
    document.getElementById('btnSubmitProvider').textContent = 'Crear Proveedor';
    providerForm.reset();
    providerForm.removeAttribute('data-edit-id');
    providerError.style.display = 'none';
    providerModalBg.style.display = 'flex';
  };

  document.getElementById('btnCancelProvider').onclick = function() {
    providerModalBg.style.display = 'none';
  };

  providerForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    providerError.style.display = 'none';
    const formData = new FormData(providerForm);
    const editId = providerForm.getAttribute('data-edit-id');
    const url = editId ? `?action=update_provider&id=${editId}` : '?action=store_provider';
    try {
      const res = await fetch(url, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const data = await res.json();
      if (data.success) {
        providerModalBg.style.display = 'none';
        window.location.reload();
      } else {
        providerError.textContent = data.error || 'Error al guardar proveedor';
        providerError.style.display = 'block';
      }
    } catch (err) {
      providerError.textContent = 'Error de red o servidor';
      providerError.style.display = 'block';
    }
  });

  deleteProviderBg.addEventListener('click', function(e) {
    if (e.target === deleteProviderBg) closeDeleteConfirm();
  });
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
