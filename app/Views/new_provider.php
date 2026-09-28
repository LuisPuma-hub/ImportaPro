<!-- Nuevo Proveedor -->
<main class="container">
  <h2>Nuevo Proveedor</h2>
  <form method="post" action="?action=store_provider">
    <label>Nombre</label>
    <input type="text" name="name" required>
    <label>País</label>
    <input type="text" name="country">
    <label>Contacto</label>
    <input type="text" name="contact">
    <label>Productos</label>
    <input type="number" name="products_count" value="0">
    <label>Rating</label>
    <input type="number" step="0.1" name="rating" value="0">
    <button class="btn primary" type="submit">Crear proveedor</button>
  </form>
</main>
