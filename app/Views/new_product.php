<!-- Nuevo Producto -->
<main class="container">
  <h2>Nuevo Producto</h2>
  <form method="post" action="?action=store_product">
    <label>Nombre</label>
    <input type="text" name="title" required>
    <div style="display:flex;gap:16px;">
      <div style="flex:1;">
        <label>Categoría</label>
        <select name="category_id">
          <option value="">Sin categoría</option>
          <?php if (isset($categories)): foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>"> <?php echo htmlspecialchars($cat['name']); ?> </option>
          <?php endforeach; endif; ?>
        </select>
      </div>
      <div style="flex:1;">
        <label>País de Origen</label>
        <input type="text" name="country">
      </div>
    </div>
    <label>País</label>
    <input type="text" name="country">
    <div style="display:flex;gap:16px;">
      <div style="flex:1;">
        <label>Precio FOB</label>
        <input type="number" name="price_cents" required>
      </div>
      <div style="flex:1;">
        <label>Stock Inicial</label>
        <input type="number" name="stock" required>
      </div>
    </div>
    <label>Descripción</label>
    <textarea name="description" rows="3"></textarea>
    <button class="btn primary" type="submit">Crear producto</button>
  </form>
</main>
