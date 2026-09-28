<?php include __DIR__ . '/layout/header.php'; ?>
<main class="container" style="padding:32px 24px;max-width:1500px;margin:0 10%;">
  <div style="margin-bottom:40px;">
    <h1 style="font-size:2.2em;color:#111;margin:0 0 8px;">Catálogo de Productos</h1>
    <p style="color:#666;font-size:1.05em;">Explora nuestra selección de productos importados</p>
  </div>

  <div style="display:flex;gap:32px;margin-bottom:40px;width:100%;align-items:flex-start;justify-content:space-between;">
    <!-- Sidebar con filtros -->
    <aside style="background:#f9f9f9;padding:24px;border-radius:8px;height:fit-content;box-sizing:border-box;flex:0 0 240px;max-width:240px;">
      <div style="margin-bottom:24px;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
          <svg style="width:20px;height:20px;color:#1769ff;" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path>
          </svg>
          <h3 style="margin:0;font-size:1.1em;color:#111;">Filtros</h3>
        </div>
      </div>

      <div style="margin-bottom:24px;">
        <label style="display:block;margin-bottom:8px;color:#333;font-weight:500;">Buscar</label>
        <form method="get" style="display:flex;gap:8px;flex-wrap:wrap;">
          <input type="text" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" placeholder="Buscar productos..." style="flex:1 1 140px;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:0.95em;min-width:0;">
          <button type="submit" style="padding:10px 16px;background:#1769ff;color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:500;flex:0 0 auto;">Buscar</button>
        </form>
      </div>

      <div style="margin-bottom:24px;">
        <label style="display:block;margin-bottom:8px;color:#333;font-weight:500;">Categoría</label>
        <select style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
          <option>Todas</option>
        </select>
      </div>

      <div style="margin-bottom:24px;">
        <label style="display:block;margin-bottom:8px;color:#333;font-weight:500;">País de Origen</label>
        <select style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
          <option>Todos</option>
        </select>
      </div>

      <div>
        <label style="display:block;margin-bottom:8px;color:#333;font-weight:500;">Rango de Precio</label>
        <div style="display:grid;grid-template-columns:1fr auto 1fr;gap:8px;align-items:center;margin-bottom:12px;">
          <input type="number" value="0" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;min-width:0;">
          <span style="text-align:center;color:#666;">-</span>
          <input type="number" value="10000" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;min-width:0;">
        </div>
      </div>
    </aside>

    <!-- Grid de productos -->
    <div style="flex:1;min-width:0;">
      <div style="color:#666;margin-bottom:20px;font-size:0.95em;">
        Mostrando <?php echo count($products); ?> productos
      </div>

      <?php if (empty($products)): ?>
        <div style="padding:40px;text-align:center;background:#f9f9f9;border-radius:8px;">
          <p style="color:#666;font-size:1.05em;">No hay productos disponibles</p>
        </div>
      <?php else: ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:24px;">
          <?php foreach ($products as $p): ?>
            <?php if ($p['stock'] > 0): ?>
              <div style="background:#fff;border:1px solid #e3e8ee;border-radius:8px;overflow:hidden;transition:all 0.3s;cursor:pointer;display:flex;flex-direction:column;" onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.12)';this.style.transform='translateY(-4px)'" onmouseout="this.style.boxShadow='none';this.style.transform='translateY(0)';">
                <div style="background:#f0f4ff;height:180px;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                  <svg style="width:80px;height:80px;color:#ccc;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                  </svg>
                </div>
                <div style="padding:16px;flex:1;display:flex;flex-direction:column;">
                  <h3 style="margin:0 0 8px;font-size:0.95em;color:#111;font-weight:500;">
                    <?php echo htmlspecialchars($p['title']); ?>
                  </h3>
                  <p style="color:#999;font-size:0.85em;margin:0 0 8px;">
                    <?php echo htmlspecialchars($p['category_name'] ?? 'Sin categoría'); ?>
                  </p>
                  <p style="color:#666;font-size:0.85em;margin:0 0 12px;flex:1;">
                    <?php echo htmlspecialchars(substr($p['description'] ?? '', 0, 100)); ?>
                  </p>
                  <div style="border-top:1px solid #e3e8ee;padding-top:12px;display:flex;align-items:center;justify-content:space-between;">
                    <div>
                      <p style="margin:0;color:#999;font-size:0.8em;text-decoration:line-through;">
                        $<?php echo number_format(($p['price_cents'] ?? 0) / 100 * 1.2, 2); ?>
                      </p>
                      <p style="margin:0;color:#1769ff;font-size:1.2em;font-weight:600;">
                        $<?php echo number_format(($p['price_cents'] ?? 0) / 100, 2); ?>
                      </p>
                    </div>
                    <button class="quote-btn" data-product-name="<?php echo htmlspecialchars($p['title']); ?>" data-product-price="<?php echo number_format(($p['price_cents'] ?? 0) / 100, 2); ?>" style="background:#1769ff;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background 0.2s;" onmouseover="this.style.background='#0055cc'" onmouseout="this.style.background='#1769ff'">
                      Cotizar
                    </button>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Paginación -->
      <?php if ($total > $perPage): ?>
        <div style="margin-top:40px;display:flex;justify-content:center;gap:8px;align-items:center;">
          <?php if ($page > 1): ?>
            <a href="?action=products&page=<?php echo $page - 1; ?><?php if ($search) echo '&search=' . urlencode($search); ?>" style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;text-decoration:none;color:#1769ff;">← Anterior</a>
          <?php endif; ?>
          <span style="color:#666;font-size:0.9em;">Página <?php echo $page; ?> de <?php echo ceil($total / $perPage); ?></span>
          <?php if ($page < ceil($total / $perPage)): ?>
            <a href="?action=products&page=<?php echo $page + 1; ?><?php if ($search) echo '&search=' . urlencode($search); ?>" style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;text-decoration:none;color:#1769ff;">Siguiente →</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Modal para cotización -->
  <div id="quote-modal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center;">
    <div class="modal-content" style="background: #fff; padding: 24px; border-radius: 8px; width: 400px; max-width: 90%;">
      <h3>Solicitar Cotización</h3>
      <p>Producto: <span id="modal-product-name"></span></p>
      <p>Precio base: $<span id="modal-product-price"></span> FOB</p>
      <label for="quantity">Cantidad:</label>
      <input type="number" id="modal-quantity" value="1" style="width: 100%; margin-bottom: 12px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
      <p>Total FOB: $<span id="modal-total-price"></span></p>
      <textarea id="modal-comments" placeholder="Especificaciones o requerimientos especiales..." style="width: 100%; margin-bottom: 12px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
      <button id="send-quote" style="background: #1769ff; color: #fff; border: none; padding: 12px 16px; border-radius: 4px; cursor: pointer;">Enviar Solicitud</button>
      <button id="close-modal" style="background: #ccc; color: #333; border: none; padding: 12px 16px; border-radius: 4px; cursor: pointer; margin-left: 8px;">Cancelar</button>
    </div>
  </div>

  <script>
    document.querySelectorAll('.quote-btn').forEach(button => {
      button.addEventListener('click', function () {
        const productName = this.getAttribute('data-product-name');
        const productPrice = this.getAttribute('data-product-price');

        // Rellenar el modal con los datos del producto
        document.getElementById('modal-product-name').innerText = productName;
        document.getElementById('modal-product-price').innerText = productPrice;
        document.getElementById('modal-total-price').innerText = productPrice;

        // Mostrar el modal
        document.getElementById('quote-modal').style.display = 'flex';
      });
    });

    // Cerrar el modal
    document.getElementById('close-modal').addEventListener('click', function () {
      document.getElementById('quote-modal').style.display = 'none';
    });
  </script>
</main>

<?php include __DIR__ . '/layout/footer.php'; ?>
