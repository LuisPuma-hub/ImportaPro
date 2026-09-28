
<?php include __DIR__ . '/layout/header.php'; ?>
  <h2>Nuevo Usuario</h2>
  <form method="post" action="?action=store_user">
    <label>Nombre</label>
    <input type="text" name="display_name" required>
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Contraseña</label>
    <input type="password" name="password" required>
    <label>Rol</label>
    <select name="role">
      <option value="cliente">Cliente</option>
      <option value="admin">Administrador</option>
    </select>
    <button class="btn primary" type="submit">Crear usuario</button>
  </form>
<?php include __DIR__ . '/layout/footer.php'; ?>
