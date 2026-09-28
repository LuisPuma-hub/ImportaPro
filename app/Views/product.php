  <?php if (!empty($_SESSION['product_message'])): ?>
    <div class="alert" style="background:#e0ffe0;color:#155724;padding:8px 16px;margin-bottom:12px;border-radius:4px;">
      <?php echo $_SESSION['product_message']; unset($_SESSION['product_message']); ?>
    </div>
  <?php endif; ?>

<?php include __DIR__ . '/layout/header.php'; ?>
<main class="container">
  <h2 style="margin-top:24px;">Gestión de Productos</h2>
  <p class="muted">Administra el catálogo de productos importados</p>
  <div class="card" style="margin-bottom:24px;">
  <form method="get" style="display:flex;gap:16px;align-items:center;">
    <input type="text" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" placeholder="Buscar productos..." style="flex:1;padding:10px 16px;border-radius:8px;border:1px solid #e3e8ee;">
    <button class="btn primary" type="submit">Buscar</button>
    <button id="btnOpenProductModal" type="button" class="btn primary" style="margin-left:auto;">+ Nuevo Producto</button>
  </form>
</div>
<div id="productModalBg" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.18);z-index:1000;align-items:center;justify-content:center;">
  <div id="productModal" style="background:#fff;max-width:600px;width:100%;min-width:400px;min-height:420px;border-radius:12px;box-shadow:0 8px 32px rgba(23,105,255,0.18);padding:40px 32px;position:relative;display:flex;flex-direction:column;align-items:center;">
    <h3 style="margin-top:0;">Nuevo Producto</h3>
    <form id="productForm">
      <label>Nombre del Producto</label>
      <input type="text" name="title" required style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;">
      <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <div style="flex:1;min-width:150px;">
          <label>Categoría (ID)</label>
          <input type="number" name="category_id" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
        </div>
        <div style="flex:1;min-width:150px;">
          <label>País de Origen</label>
          <input type="text" name="country" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
        </div>
      </div>
      <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <div style="flex:1;min-width:150px;">
          <label>Precio FOB</label>
          <input type="number" name="price_cents" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
        </div>
        <div style="flex:1;min-width:150px;">
          <label>Stock Inicial</label>
          <input type="number" name="stock" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
        </div>
      </div>
      <label>Descripción</label>
      <textarea name="description" rows="3" style="width:100%;margin-bottom:12px;padding:8px;border:1px solid #ddd;border-radius:4px;"></textarea>
      <div id="productFormError" class="error" style="display:none;margin-bottom:12px;"></div>
      <div style="display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap;">
        <button type="button" id="btnCancelProduct" class="btn" style="flex:1;min-width:100px;background:#fff;color:#1769ff;border:1px solid #1769ff;">Cancelar</button>
        <button type="submit" id="btnSubmitProduct" class="btn primary" style="flex:1;min-width:100px;">Crear Producto</button>
      </div>
    </form>
  </div>
</div>
  <div class="card" style="width:100%;margin:0 auto;">
  <table class="product-table">
    <thead>
      <tr>
        <th>PRODUCTO</th>
        <th>CATEGORÍA</th>
        <th>PAÍS</th>
        <th>PRECIO FOB</th>
        <th>STOCK</th>
        <th>ACCIONES</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $p): ?>
      <tr>
        <td>
          <span class="product-icon">&#128230;</span>
          <a href="?action=product_detail&id=<?php echo $p['id']; ?>" class="product-link"><?php echo htmlspecialchars($p['title']); ?></a>
        </td>
        <td><?php echo htmlspecialchars($p['category_name'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($p['country'] ?? ''); ?></td>
        <td><span class="product-price">$<?php echo htmlspecialchars($p['price_cents'] / 100); ?></span></td>
        <td>
          <?php if ($p['stock'] > 0): ?>
            <span class="stock-badge stock-ok"><?php echo htmlspecialchars($p['stock']); ?> unidades</span>
          <?php else: ?>
            <span class="stock-badge stock-bad">0 unidades</span>
          <?php endif; ?>
        </td>
        <td>
          <a href="#" class="action-icon edit" onclick="openEditProductModal(<?php echo $p['id']; ?>); return false;"><span>&#128397;</span></a>
          <a href="#" class="action-icon delete" onclick="openDeleteProductConfirm(<?php echo $p['id']; ?>); return false;"><span>&#128465;</span></a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Confirmación eliminación -->
<div id="deleteConfirmBg" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.32);z-index:1200;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:10px;box-shadow:0 12px 30px rgba(0,0,0,0.15);padding:24px;max-width:360px;width:90%;text-align:center;">
    <h4 style="margin-top:0;margin-bottom:12px;color:#111;">Eliminar producto</h4>
    <p style="margin:0 0 16px;color:#444;">¿Seguro que deseas eliminar este producto?</p>
    <div style="display:flex;gap:12px;justify-content:flex-end;">
      <button id="btnDeleteConfirmNo" class="btn" style="flex:1;background:#f5f5f5;color:#444;border:1px solid #d9d9d9;">Cancelar</button>
      <button id="btnDeleteConfirmYes" class="btn primary" style="flex:1;">Eliminar</button>
    </div>
  </div>
</div>

<!-- Toast mensajes -->
<div id="toast" style="position:fixed;bottom:24px;right:24px;background:#1769ff;color:#fff;padding:12px 16px;border-radius:8px;box-shadow:0 10px 24px rgba(23,105,255,0.25);opacity:0;pointer-events:none;transition:opacity 0.2s ease-out;z-index:1300;"></div>

<script>
  const productModalBg = document.getElementById('productModalBg');
  const productForm = document.getElementById('productForm');
  const toastEl = document.getElementById('toast');
  const deleteConfirmBg = document.getElementById('deleteConfirmBg');
  const btnDeleteYes = document.getElementById('btnDeleteConfirmYes');
  const btnDeleteNo = document.getElementById('btnDeleteConfirmNo');
  const productError = document.getElementById('productFormError');
  let pendingDeleteId = null;

  function showToast(message, isError = false) {
    toastEl.textContent = message;
    toastEl.style.background = isError ? '#e53935' : '#1769ff';
    toastEl.style.opacity = '1';
    setTimeout(() => { toastEl.style.opacity = '0'; }, 2000);
  }

  function openDeleteProductConfirm(id) {
    pendingDeleteId = id;
    deleteConfirmBg.style.display = 'flex';
  }

  function closeDeleteConfirm() {
    deleteConfirmBg.style.display = 'none';
    pendingDeleteId = null;
  }

  btnDeleteNo.addEventListener('click', closeDeleteConfirm);

  btnDeleteYes.addEventListener('click', async function() {
    if (!pendingDeleteId) return;
    try {
      const res = await fetch(`?action=delete_product&id=${pendingDeleteId}`);
      closeDeleteConfirm();
      if (res.ok) {
        showToast('Producto eliminado correctamente');
        setTimeout(() => window.location.reload(), 2000);
      } else {
        showToast('Error al eliminar', true);
      }
    } catch (err) {
      closeDeleteConfirm();
      showToast('Error de red', true);
    }
  });

  function openEditProductModal(id) {
    fetch('?action=get_product&id=' + id)
      .then(res => res.json())
      .then(data => {
        if (data && data.id) {
          document.querySelector('#productModal h3').textContent = 'Editar Producto';
          document.getElementById('btnSubmitProduct').textContent = 'Guardar Cambios';
          productForm.querySelector('[name=title]').value = data.title || '';
          productForm.querySelector('[name=category_id]').value = data.category_id || '';
          productForm.querySelector('[name=country]').value = data.country || '';
          productForm.querySelector('[name=price_cents]').value = data.price_cents || '';
          productForm.querySelector('[name=stock]').value = data.stock || '';
          productForm.querySelector('[name=description]').value = data.description || '';
          productForm.setAttribute('data-edit-id', id);
          productError.style.display = 'none';
          productModalBg.style.display = 'flex';
        }
      });
  }

  document.getElementById('btnOpenProductModal').onclick = function() {
    document.querySelector('#productModal h3').textContent = 'Nuevo Producto';
    document.getElementById('btnSubmitProduct').textContent = 'Crear Producto';
    productForm.reset();
    productForm.removeAttribute('data-edit-id');
    productError.style.display = 'none';
    productModalBg.style.display = 'flex';
  };

  document.getElementById('btnCancelProduct').onclick = function() {
    productModalBg.style.display = 'none';
  };

  // Cerrar confirm con click en fondo
  deleteConfirmBg.addEventListener('click', function(e) {
    if (e.target === deleteConfirmBg) closeDeleteConfirm();
  });
</script>
<?php
  $totalPages = isset($total) ? ceil($total / 10) : 1;
  $currentPage = intval($_GET['page'] ?? 1);
  if ($totalPages > 1):
?>
  <nav style="margin:24px 0;text-align:center;">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="?action=products&page=<?php echo $i; ?><?php if (!empty($_GET['search'])) echo '&search=' . urlencode($_GET['search']); ?>" class="btn<?php echo $i == $currentPage ? ' primary' : ''; ?>" style="margin:0 4px;"> <?php echo $i; ?> </a>
    <?php endfor; ?>
  </nav>
<?php endif; ?>
<?php include __DIR__ . '/layout/footer.php'; ?>