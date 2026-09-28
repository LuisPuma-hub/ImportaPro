<!-- Editar Producto -->
<main class="container">
  <h2>Editar Producto</h2>
  <form method="post" action="?action=update_product">
    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
    <label>Nombre</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>
    <div style="display:flex;gap:16px;">
      <div style="flex:1;">
        <label>Categoría</label>
        <select name="category_id">
          <option value="">Sin categoría</option>
          <?php if (isset($categories)): foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>" <?php if ($product['category_id'] == $cat['id']) echo 'selected'; ?>>
              <?php echo htmlspecialchars($cat['name']); ?>
            </option>
          <?php endforeach; endif; ?>
        </select>
      </div>
    </div>
    <label>País</label>
    <input type="text" name="country" value="<?php echo htmlspecialchars($product['country'] ?? ''); ?>">
    <div style="display:flex;gap:16px;">
      <div style="flex:1;">
        <label>Precio FOB</label>
        <input type="number" name="price_cents" value="<?php echo htmlspecialchars($product['price_cents']); ?>" required>
      </div>
      <div style="flex:1;">
        <label>Stock Inicial</label>
        <input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
      </div>
    </div>
    <label>Descripción</label>
    <textarea name="description" rows="3"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
    <button class="btn primary" type="submit">Guardar cambios</button>
  </form>
</main>
