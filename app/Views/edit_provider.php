<!-- Editar Proveedor -->
<main class="container">
  <h2>Editar Proveedor</h2>
  <form method="post" action="?action=update_provider">
    <input type="hidden" name="id" value="<?php echo $provider['id']; ?>">
    <label>Nombre</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($provider['name']); ?>" required>
    <label>País</label>
    <input type="text" name="country" value="<?php echo htmlspecialchars($provider['country']); ?>">
    <label>Contacto</label>
    <input type="text" name="contact" value="<?php echo htmlspecialchars($provider['contact']); ?>">
    <label>Productos</label>
    <input type="number" name="products_count" value="<?php echo htmlspecialchars($provider['products_count'] ?? '0'); ?>">
    <label>Rating</label>
    <input type="number" step="0.1" name="rating" value="<?php echo htmlspecialchars($provider['rating'] ?? '0'); ?>">
    <button class="btn primary" type="submit">Guardar cambios</button>
  </form>
</main>
