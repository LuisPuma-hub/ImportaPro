<!-- Editar Usuario -->
<main class="container">
  <h2>Editar Usuario</h2>
  <form method="post" action="?action=update_user">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
    <label>Nombre</label>
    <input type="text" name="display_name" value="<?php echo htmlspecialchars($user['display_name']); ?>" required>
    <label>Email</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    <label>Rol</label>
    <select name="role">
      <option value="cliente" <?php if(!$user['is_admin']) echo 'selected'; ?>>Cliente</option>
      <option value="admin" <?php if($user['is_admin']) echo 'selected'; ?>>Administrador</option>
    </select>
    <button class="btn primary" type="submit">Guardar cambios</button>
  </form>
</main>
